<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 11. 3. 2019
 * Time: 14:49
 */

namespace App\Helpers\Monitoring\ImportFlow;

use App\Helpers\Monitoring\ImportFlow\Connection\MonitoringCacheConnection;
use Monkey\Connections\MDDatabaseConnections;

class StepPoolMonitoring
{

    const AVERAGE_FLOW_RUNTIME = "averageFlowRuntime";
    const BASE_GRAPH = "baseGraph";
    const MONITORING_LOG_TABLE = "if_monitoring";

const ATTRIBUTES = array(
                        array(
                        "id" => 1,
                        "name" => "average_run_time",
                        ),
                        array(
                        "id" => 2,
                        "name" => "count_long_run_time_flows",
                        ),
                        array(
                        "id" => 3,
                        "name" => "long_average_import_time_to_start",
                        ),
                        array(
                        "id" => 4,
                        "name" => "long_average_etl_time_to_start",
                        ),
                        array(
                        "id" => 5,
                        "name" => "long_average_calc_time_to_start",
                        ),
                        array(
                        "id" => 6,
                        "name" => "long_average_output_time_to_start",
                        ),
                        array(
                        "id" => 7,
                        "name" => "long_average_import_run_time",
                        ),
                        array(
                        "id" => 8,
                        "name" => "long_average_etl_run_time",
                        ),
                        array(
                        "id" => 9,
                        "name" => "long_average_calc_run_time",
                        ),
                        array(
                        "id" => 10,
                        "name" => "long_average_output_run_time",
                        ),
                    );



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

        MDDatabaseConnections::getImportSupportConnection()->table(self::MONITORING_LOG_TABLE);

        return $out;
    }

    private function compare

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





}


