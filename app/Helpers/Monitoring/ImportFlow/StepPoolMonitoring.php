<?php
/**
 * Created by PhpStorm.
 * User: Fingarfae
 * Date: 11. 3. 2019
 * Time: 14:49
 */

namespace App\Helpers\Monitoring\ImportFlow;

class StepPoolMonitoring
{




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
    public function selectBaseStepData(){
        $data = $this->getDataMiner()->getBaseGraphData();
        return $this->getDataCalculator()->calculateDataForGraph($data);
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





}


