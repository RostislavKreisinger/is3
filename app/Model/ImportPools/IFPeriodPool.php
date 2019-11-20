<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Relations\HasOne;
use Monkey\Constants\ImportFlow\Pools\Pools;

/**
 * Class IFPeriodPool
 *
 * @package App\Model\ImportPools
 * @property-read string $unique
 * @property-read IFImportPool $importPool
 * @property-read \App\Model\Project $project
 * @property-read \App\Model\Resource $resource
 * @mixin \Eloquent
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
     * @return IFPeriodPool
     */
    public function activate(): IFPeriodPool {
        $this->active = Pools::ACTIVE;
        $this->ttl = Pools::TTL_DEFAULT;
        return $this;
    }

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
