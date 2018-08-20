<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class IFStepPool
 * @package App\Model\ImportPools
 */
abstract class IFStepPool extends IFPool {
    /**
     * @var array $appends
     */
    protected $appends = ['if_step'];

    /**
     * @var string $ifStep
     */
    protected $ifStep;

    /**
     * @param Builder $query
     * @param array $active
     * @return Builder
     */
    public function scopeWhereActiveIn(Builder $query, array $active): Builder {
        return $query->whereIn('active', $active);
    }

    /**
     * @param Builder $query
     * @param string $datetime
     * @return Builder
     */
    public function scopeWhereOlderThan(Builder $query, string $datetime): Builder {
        return $query->where('created_at', '<', $datetime);
    }

    /**
     * @return BelongsTo
     */
    public function controlPool(): BelongsTo {
        return $this->belongsTo(IFControlPool::class, 'unique', 'unique');
    }

    /**
     * @return string
     */
    public function getIfStepAttribute(): string {
        return $this->ifStep;
    }
}