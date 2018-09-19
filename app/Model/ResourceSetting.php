<?php

namespace App\Model;

/**
 * Class ResourceSetting
 * @package App\Model
 */
class ResourceSetting extends Model {
    protected $connection = 'mysql-master-app';
    protected $table = 'resource_setting';
    
    protected $guarded = [];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function resource() {
        return $this->belongsTo(Resource::class);
    }
}