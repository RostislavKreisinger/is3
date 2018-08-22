<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project
 * @package App\Model
 */
class Project extends Model {
    use SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'project';
    
    protected $guarded = [];

    /**
     * @return BelongsToMany
     */
    public function getResources() {
        return $this->belongsToMany(Resource::class, "resource_setting", "project_id", "resource_id");
    }

    /**
     * @param int|null $resourceId
     * @return HasMany
     */
    public function resourceSettings(int $resourceId = null): HasMany {
        $builder = $this->hasMany(ResourceSetting::class);

        if (!is_null($resourceId)) {
            $builder->where('resource_id', '=', $resourceId);
        }

        return $builder;
    }

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user()->first();
    }

    /**
     * @return Collection
     */
    public function getAutoReports() {
        return $this->hasMany(AutoReportPool::class)->get();
    }
}
