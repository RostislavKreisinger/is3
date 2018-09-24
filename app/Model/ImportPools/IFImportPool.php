<?php

namespace App\Model\ImportPools;


/**
 * Class IFImportPool
 * @package App\Model\ImportPools
 */
class IFImportPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_import';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'Import';
}