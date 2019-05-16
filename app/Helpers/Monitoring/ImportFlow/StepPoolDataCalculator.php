<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 13.3.2019
 * Time: 11:53
 */

namespace App\Helpers\Monitoring\ImportFlow;


class StepPoolDataCalculator
{
    /**
     * @var int
     */
    private $averageFlowRuntime = 0;

    /**
     * @var int
     */
    private $countFlows = 0;
    
    /**
     * @var int
     */
    private $maximalFlowRunTime = 0;

    /**
     * @var GraphRowData
     */
    private $averageRow;

    /**
     * @param array $data
     * @return GraphRowData[]
     */
    public function calculateDataForGraph(array $data){
        $this->preCalculateGlobalVariables($data);

        $rows = $this->calculateGraphRows($data);
        $rows[$this->getAverageRow()->getUnique()] = $this->getAverageRow();


        uasort($rows, function(GraphRowData $a,GraphRowData $b){
            return ($a->getFlowRuntime() <= $b->getFlowRuntime()) ? -1 : 1;
        });
        return $rows;
    }

    /**
     * @param array $data
     * @return GraphRowData[]
     */
    private function calculateGraphRows(array $data){
        $out = [];
        foreach($data as $row){
            $out[$row->unique] = $this->getGraphRowData($row);
        }
        return $out;
    }


    /**
     * @param \stdClass $row
     * @param bool $isAverage
     * @return GraphRowData
     */
    private function getGraphRowData(\stdClass $row, bool $isAverage = false){

        $graphRow = new GraphRowData($row->unique, $row->flow_runtime, $this->getMaximalFlowRunTime(), $isAverage);

        $graphRow->setImportTimeToRun((int)$row->i_time_to_start);
        $graphRow->setImportTimeToRunPercent($graphRow->getPercent($row->flow_runtime, (int)$row->i_time_to_start));
        $graphRow->setImportStepRuntime((int)$row->i_runtime);
        $graphRow->setImportStepRuntimePercent($graphRow->getPercent($row->flow_runtime, (int)$row->i_runtime));

        $graphRow->setEtlTimeToRun((int)$row->e_time_to_start);
        $graphRow->setEtlTimeToRunPercent($graphRow->getPercent($row->flow_runtime, (int)$row->e_time_to_start));
        $graphRow->setEtlStepRuntime((int)$row->e_runtime);
        $graphRow->setEtlStepRuntimePercent($graphRow->getPercent($row->flow_runtime, (int)$row->e_runtime));

        $graphRow->setCalcTimeToRun((int)$row->c_time_to_start);
        $graphRow->setCalcTimeToRunPercent($graphRow->getPercent($row->flow_runtime, (int)$row->c_time_to_start));
        $graphRow->setCalcStepRuntime((int)$row->c_runtime);
        $graphRow->setCalcStepRuntimePercent($graphRow->getPercent($row->flow_runtime, (int)$row->c_runtime));


        $graphRow->setOutputTimeToRun((int)$row->o_time_to_start);
        $graphRow->setOutputTimeToRunPercent($graphRow->getPercent($row->flow_runtime, (int)$row->o_time_to_start));
        $graphRow->setOutputStepRuntime((int)$row->o_runtime);
        $graphRow->setOutputStepRuntimePercent($graphRow->getPercent($row->flow_runtime, (int)$row->o_runtime));

        if($row->flow_runtime > $this->getAverageFlowRuntime()){
            $graphRow->setBiggerThanAverage(true);
        }

        return $graphRow;
    }


    /**
     * @param array $data
     */
    private function preCalculateGlobalVariables(array $data){
        $maximalFlowRuntime = 0;
        $sumRuntime = 0;
        $this->setCountFlows(count($data));
        $averageRow = new \stdClass();
        $averageRow->unique = "average";
        $averageRow->i_time_to_start = 0;
        $averageRow->i_time_to_start_c = 0;
        $averageRow->i_runtime = 0;
        $averageRow->i_runtime_c = 0;

        $averageRow->e_time_to_start = 0;
        $averageRow->e_time_to_start_c = 0;
        $averageRow->e_runtime = 0;
        $averageRow->e_runtime_c = 0;

        $averageRow->c_time_to_start = 0;
        $averageRow->c_time_to_start_c = 0;
        $averageRow->c_runtime = 0;
        $averageRow->c_runtime_c = 0;

        $averageRow->o_time_to_start = 0;
        $averageRow->o_time_to_start_c = 0;
        $averageRow->o_runtime = 0;
        $averageRow->o_runtime_c = 0;

        foreach($data as $row){
            if($row->flow_runtime > $maximalFlowRuntime){
                $maximalFlowRuntime = $row->flow_runtime;
            }
            $sumRuntime += $row->flow_runtime;

            $this->countAverageValue("i",$averageRow,$row);
            $this->countAverageValue("e",$averageRow,$row);
            $this->countAverageValue("c",$averageRow,$row);
            $this->countAverageValue("o",$averageRow,$row);
        }

        $this->setMaximalFlowRunTime($maximalFlowRuntime);
        $this->setAverageFlowRuntime($sumRuntime, $this->getCountFlows());

        $averageRow->i_time_to_start = $averageRow->i_time_to_start_c != 0 ? round($averageRow->i_time_to_start / $averageRow->i_time_to_start_c,0) : null;
        $averageRow->i_runtime = $averageRow->i_runtime_c != 0 ? round($averageRow->i_runtime / $averageRow->i_runtime_c,0) : null;

        $averageRow->e_time_to_start = $averageRow->e_time_to_start_c != 0 ? round($averageRow->e_time_to_start / $averageRow->e_time_to_start_c,0) : null;
        $averageRow->e_runtime = $averageRow->e_runtime_c != 0 ? round($averageRow->e_runtime / $averageRow->e_runtime_c,0) : null;

        $averageRow->c_time_to_start = $averageRow->c_time_to_start_c != 0 ? round($averageRow->c_time_to_start / $averageRow->c_time_to_start_c,0) : null;
        $averageRow->c_runtime = $averageRow->c_runtime_c != 0 ? round($averageRow->c_runtime / $averageRow->c_runtime_c,0) : null;

        $averageRow->o_time_to_start = $averageRow->o_time_to_start_c != 0 ? round($averageRow->o_time_to_start / $averageRow->o_time_to_start_c,0) : null;
        $averageRow->o_runtime = $averageRow->o_runtime_c != 0 ? round($averageRow->o_runtime / $averageRow->o_runtime_c,0) : null;

        $averageRow->flow_runtime = $this->getAverageFlowRuntime();

        $this->setAverageRow($this->getGraphRowData($averageRow, true));
    }

    private function countAverageValue(string $prefix, $averageRow,$row){
        $timeToStart = $prefix."_time_to_start";
        $timeToStartCount = $timeToStart."_c";
        if(!is_null($row->$timeToStart)){
            $averageRow->$timeToStart += $row->$timeToStart;
            $averageRow->$timeToStartCount++;
        }

        $runtime = $prefix."_runtime";
        $runtimeCount = $runtime."_c";
        if(!is_null($row->$runtime)){
            $averageRow->$runtime += $row->$runtime;
            $averageRow->$runtimeCount++;
        }
    }




    /**
     * @return float
     */
    public function getAverageFlowRuntime(): int
    {
        return $this->averageFlowRuntime;
    }

    /**
     * @param int $sumFlowRuntime
     * @param int $countFlows
     * @return StepPoolDataCalculator
     */
    public function setAverageFlowRuntime(int $sumFlowRuntime, int $countFlows): StepPoolDataCalculator
    {
        $this->averageFlowRuntime = (int)floor($sumFlowRuntime / $countFlows);
        return $this;
    }

    /**
     * @return int
     */
    public function getCountFlows(): int
    {
        return $this->countFlows;
    }

    /**
     * @param int $countFlows
     * @return StepPoolDataCalculator
     */
    public function setCountFlows(int $countFlows): StepPoolDataCalculator
    {
        $this->countFlows = $countFlows;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaximalFlowRunTime(): int
    {
        return $this->maximalFlowRunTime;
    }

    /**
     * @param int $maximalFlowRunTime
     * @return StepPoolDataCalculator
     */
    public function setMaximalFlowRunTime(int $maximalFlowRunTime): StepPoolDataCalculator
    {
        $this->maximalFlowRunTime = $maximalFlowRunTime;
        return $this;
    }

    /**
     * @return GraphRowData
     */
    public function getAverageRow(): GraphRowData
    {
        return $this->averageRow;
    }

    /**
     * @param GraphRowData $averageRow
     * @return StepPoolDataCalculator
     */
    public function setAverageRow(GraphRowData $averageRow): StepPoolDataCalculator
    {
        $this->averageRow = $averageRow;
        return $this;
    }


}