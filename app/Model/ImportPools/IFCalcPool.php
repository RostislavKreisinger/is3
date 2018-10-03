<?php

namespace App\Model\ImportPools;


/**
 * Class IFCalcPool
 * @package App\Model\ImportPools
 */
class IFCalcPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_calc';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'Calc';
}