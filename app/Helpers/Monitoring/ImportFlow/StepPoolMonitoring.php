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

    const AVERAGE_FLOW_RUNTIME = "averageFlowRuntime";
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
     * @return int
     */
    public function checkAverageFlowRuntime(){

        $graphData = $this->getGraphData();

        $out = 0;
        foreach($graphData as $rowData){
            if($rowData->isAverage()){
                $out = $rowData->getFlowRuntime();
            }
        }



        return $out;
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
            if(is_null($row['start_issue'])){
                $values['start_issue'] = date("Y-m-d H:i:s");
            }
            $values["number_of_sequels"]++;

            if($values["number_of_sequels"] == self::EXCEEDING_COUNT){
                //self messages
            }

            MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE)
                ->where("id","=",$row->id)
                ->update($values);
        }
    }

    private function sendSlackMessageToImportFlowChannel(string $attributeName, string $startIssue, int $numberOfSequence){

        $message = "Exceeded the limit '{$attributeName}' in {$numberOfSequence} consecutive attempts, the first occurrence of the problem was recorded at {$startIssue}";

        try {
            Slack::getInstance()->onPAImportDone($message);
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
     * @param $attrName
     * @return MonitoringAttributeSetting
     */

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


