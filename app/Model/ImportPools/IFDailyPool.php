<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class IFDailyPool
 * @package App\Model\ImportPools
 */
class IFDailyPool extends IFPool {
    use SoftDeletes;

    /**
     * @var string $table
     */
    protected $table = 'if_daily';

    /**
     * @return HasOne
     */
    public function importPool(): HasOne {
        return $this->hasOne(IFImportPool::class, 'if_import_id', 'id');
    }
}