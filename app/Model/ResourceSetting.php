<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ResourceSetting
 * @package App\Model
 */
class ResourceSetting extends Model {
    protected $connection = 'mysql-master-app';
    protected $table = 'resource_setting';
    
    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function resourceName(): BelongsTo {
        return $this->belongsTo(Resource::class, 'resource_id')
            ->select(['id', 'name']);
    }

    /**
     * @param Builder $query
     * @param int $active
     * @return Builder
     */
    public function scopeWhereActive(Builder $query, int $active): Builder {
        return $query->where('active', '=', $active);
    }
}