<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 11. 3. 2019
 * Time: 14:49
 */

namespace App\Helpers\Monitoring\ImportFlow;

use App\Helpers\Monitoring\ImportFlow\Connection\MonitoringCacheConnection;
use App\Helpers\Monitoring\ImportFlow\Exception\UnknownMonitoringAttributeException;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Raven\Sentry;
use Monkey\Slack\Slack;

class StepPoolMonitoring
{

    const EXCEEDING_COUNT = 10;

    const AVERAGE_FLOW_RUNTIME = "average_run_time";

    const BASE_GRAPH = "baseGraph";
    const MONITORING_LOG_TABLE = "if_monitoring";
    const MONITORING_ATTRIBUTES_SETTING_TABLE = "if_monitoring_attribute";


    /**
     * @var StepPoolDataMiner
     */
    private $dataMiner;

    /**
     * @var StepPoolDataCalculator
     */
    private $dataCalculator;


    /**
     * @return GraphRowData[]
     */
    public function getGraphData(){

        $out = MonitoringCacheConnection::getMonitoringCacheConnection()->remember(self::BASE_GRAPH, 0.25, function (){
            $data = $this->getDataMiner()->getBaseGraphData();
            return $this->getDataCalculator()->calculateDataForGraph($data);
        });

        return $out;
    }


    /**
     * @throws UnknownMonitoringAttributeException
     */
    public function checkAverageFlowAttributes(){
        $graphData = $this->getGraphData();

        foreach($graphData as $rowData){
            if($rowData->isAverage()){

                $this->compareAttribute("average_run_time", $rowData->getFlowRuntime());
                $this->compareAttribute("long_average_import_time_to_start", $rowData->getImportTimeToRun());
                $this->compareAttribute("long_average_etl_time_to_start", $rowData->getEtlTimeToRun());
                $this->compareAttribute("long_average_calc_time_to_start", $rowData->getCalcTimeToRun());
                $this->compareAttribute("long_average_output_time_to_start", $rowData->getOutputTimeToRun());

                $this->compareAttribute("long_average_import_run_time", $rowData->getImportStepRuntime());
                $this->compareAttribute("long_average_etl_run_time", $rowData->getEtlStepRuntime());
                $this->compareAttribute("long_average_calc_run_time", $rowData->getCalcStepRuntime());
                $this->compareAttribute("long_average_output_run_time", $rowData->getOutputStepRuntime());
            }
        }
    }

    /**
     * @throws UnknownMonitoringAttributeException
     */
    public function checkCountLongRunTimeFlows(){
        $graphData = $this->getGraphData();

        $averageFlowRuntime = 0;
        foreach($graphData as $rowData){
            if($rowData->isAverage()){
                $averageFlowRuntime = $rowData->getFlowRuntime();
            }
        }

        $count = 0;
        foreach($graphData as $rowData){
            if(!$rowData->isAverage() AND $rowData->getFlowRuntime() > $averageFlowRuntime){
                $count++;
            }
        }
        $this->compareAttribute("count_long_run_time_flows", $count);
    }

    /**
     * @throws UnknownMonitoringAttributeException
     */
    public function checkCountHigherWorkflowDifficulty(){
        $data = $this->getDataMiner()->getDifficultyData();
        $count = count($data);
        $this->compareAttribute("count_higher_difficulty_flows", $count);
    }

    /**
     * @param string $attributeName
     * @param int $value
     * @throws UnknownMonitoringAttributeException
     */
    private function compareAttribute(string $attributeName, int $value){

        $attributeSetting = $this->getAttributeRowSetting($attributeName);

        if($attributeSetting->getCriticalValue() < $value){
            $this->logExceededLimit($attributeSetting);
        }else{
            $this->resetLog($attributeSetting);
        }
    }

    /**
     * @param MonitoringAttributeSetting $attributeSetting
     */
    private function logExceededLimit(MonitoringAttributeSetting $attributeSetting){
        $row = MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE)
            ->where("attribute_id","=",$attributeSetting->getId())
            ->whereNull("confirm_resolution")
            ->orderBy("id","DESC")
            ->first()
        ;

        if(is_null($row)){
            $values = [];
            $values["attribute_id"] = $attributeSetting->getId();
            $values["number_of_sequels"] = 1;
            $values["start_issue"] = date("Y-m-d H:i:s");
            MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE)->insert($values);
        }else{
            $values = [];
            $startIssue = $row->start_issue;
            if(is_null($row->start_issue)){
                $values['start_issue'] = date("Y-m-d H:i:s");
                $startIssue = $values['start_issue'];
            }
            $values["number_of_sequels"] = $row->number_of_sequels + 1;

            if(($values["number_of_sequels"] % self::EXCEEDING_COUNT) == 0){
                $priorityLevel = $values["number_of_sequels"] % self::EXCEEDING_COUNT;
                $this->sendSlackMessageToImportFlowChannel($attributeSetting->getName(), $attributeSetting->getCriticalValue(), $startIssue, $values["number_of_sequels"], $priorityLevel);
            }

            MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE)
                ->where("id","=",$row->id)
                ->update($values);
        }
    }

    /**
     * @param string $attributeName
     * @param int $criticalValue
     * @param string $startIssue
     * @param int $numberOfSequence
     */
    private function sendSlackMessageToImportFlowChannel(string $attributeName, int $criticalValue, string $startIssue, int $numberOfSequence, int $priorityLevel){


        $emojis = ["🚨","💣","🌋","🌀"];
        $prioritization = [];

        for($i = 0; $i < $priorityLevel; $i++){
            $prioritization[] = $emojis[ $i % 4 ];
        }

        $prioritization = "\n".implode(" ", $prioritization)."\n";

        $message = "{$prioritization}Exceeded the limit '{$attributeName}' ({$criticalValue}) in {$numberOfSequence} consecutive attempts, the first occurrence of the problem was recorded at {$startIssue}{$prioritization}";

        try {
            vdEcho("Try to send message to slack: {$message}");
            Slack::getInstance()->onIFMonitoringLimitExceeded($message);
            vdEcho("Message to slack was send.");
        } catch (\Exception $e) {
            Sentry::error("Sending message to slack failed;", ['reason'=>$e->getMessage()]);
        }
    }

    /**
     * @param MonitoringAttributeSetting $attributeSetting
     */
    private function resetLog(MonitoringAttributeSetting $attributeSetting){
        
        $row = MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE)
            ->where("attribute_id","=",$attributeSetting->getId())
            ->where("number_of_sequels","<",self::EXCEEDING_COUNT)
            ->whereNull("confirm_resolution")
            ->orderBy("id","DESC")
            ->first()
        ;

        if(!is_null($row)){

            $values = [];
            $values["number_of_sequels"] = 0;
            $values["start_issue"] = null;
            $values["solver_user_id"] = null;


            MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE)
                ->where("id","=",$row->id)
                ->update($values);

        }
        
    }

    /**
     * @return StepPoolDataMiner
     */
    public function getDataMiner(): StepPoolDataMiner
    {
        if(is_null($this->dataMiner)){
            $this->dataMiner = new StepPoolDataMiner();
        }
        return $this->dataMiner;
    }

    /**
     * @return StepPoolDataCalculator
     */
    public function getDataCalculator(): StepPoolDataCalculator
    {
        if(is_null($this->dataCalculator)){
            $this->dataCalculator = new StepPoolDataCalculator();
        }
        return $this->dataCalculator;
    }

    /**
     * @param string $attrName
     * @return MonitoringAttributeSetting
     * @throws UnknownMonitoringAttributeException
     */
    private function getAttributeRowSetting(string $attrName){
        $out = MonitoringCacheConnection::getMonitoringCacheConnection()->remember(self::MONITORING_ATTRIBUTES_SETTING_TABLE, 60, function (){
            return $this->loadAttributesSetting();
        });

        if(!isset($out[$attrName])){
            throw new UnknownMonitoringAttributeException($attrName);
        }
        return $out[$attrName];
    }

    /**
     * @return MonitoringAttributeSetting[]
     */
    private function loadAttributesSetting(){
        $rows = MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_ATTRIBUTES_SETTING_TABLE)->get();
        $out = [];
        foreach($rows as $row){
            $out[$row->name] = new MonitoringAttributeSetting($row->id, $row->name, $row->criticalValue);
        }

        return $out;
    }





}


