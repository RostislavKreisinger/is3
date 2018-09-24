<?php

namespace App\Model\ImportPools;


/**
 * Class IFEtlPool
 * @package App\Model\ImportPools
 */
class IFEtlPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_etl';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'ETL';
}