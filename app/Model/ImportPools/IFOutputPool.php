<?php

namespace App\Model\ImportPools;


/**
 * Class IFOutputPool
 * @package App\Model\ImportPools
 */
class IFOutputPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_output';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'Output';
}