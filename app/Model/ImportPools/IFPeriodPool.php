<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Monkey\Constants\ImportFlow\Pools\Pools;

/**
 * Class IFPeriodPool
 *
 * @package App\Model\ImportPools
 * @property-read string $unique
 * @property-read IFImportPool $importPool
 * @property-read Project $project
 * @property-read Resource $resource
 * @mixin Eloquent
 */
abstract class IFPeriodPool extends IFPool {
    /**
     * The accessors to append to the model's array form.
     *
     * @var array $appends
     */
    protected $appends = ['period', 'unique'];

    /**
     * @var string $period
     */
    protected $period;

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

    /**
     * @return string
     */
    public function getPeriodAttribute(): string {
        return $this->period;
    }
}
