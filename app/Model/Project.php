<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'project';
    
    protected $guarded = [];
    
    public function getResources() {
        return $this->belongsToMany(Resource::class, "resource_setting", "project_id", "resource_id"); //->where('resource_setting.active', '!=', 3);
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

    /**
     * @return BelongsTo
     */
    public function eshopTypeName(): BelongsTo {
        return $this->belongsTo(EshopType::class, 'eshop_type_id')->select(['id', 'name']);
    }
}
