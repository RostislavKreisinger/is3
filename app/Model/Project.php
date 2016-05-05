<?php

namespace App\Model;

use Eloquent;

class Project extends Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'project';
    
    protected $guarded = [];
    
    public function getResources() {
        return $this->belongsToMany(Resource::class, "resource_setting", "project_id", "resource_id")->where('active', '!=', 3);
    }
    
    public function getResourceSettings() {
        return $this->hasMany(ResourceSetting::class, "resource_setting", "project_id")->where('active', '!=', 3);
    }
    
    public function getUser() {
        $user = User::find($this->user_id); 
        return $user; //  $this->hasOne(User::class)->first();
    }
    
    public function getAutomattestInvalidProject() {
        // $this->join('resource_setting', $one, $operator, $two)
    }

}
