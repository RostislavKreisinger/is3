<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class IFPeriodPool
 * @package App\Model\ImportPools
 */
class IFPeriodPool extends IFPool {
    /**
     * @var array $appends
     */
    protected $appends = ['unique'];

    /**
     * @var string $unique
     */
    protected $unique;

    /**
     * @return HasOne
     */
    public function importPool(): HasOne {
        return $this->hasOne(IFImportPool::class, 'id', 'if_import_id');
    }

    /**
     * @return string
     */
    public function getUniqueAttribute(): string {
        if ($this->unique === null) {
            $importPool = $this->importPool()->first();

            if ($importPool === null) {
                $this->unique = 'Import Pool not set';
            } else {
                $this->unique = $importPool->unique;
            }
        }

        return $this->unique;
    }
}