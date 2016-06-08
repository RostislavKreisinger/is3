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
    
    public function getResourceSettings($resource_id = null) {
        $builder = $this->hasMany(ResourceSetting::class)->where('active', '!=', 3);
        if($resource_id){
            $builder->where('resource_id', '=', $resource_id);
        }
        return $builder;
    }
    
    public function getUser() {
        $user = User::find($this->user_id); 
        return $user; //  $this->hasOne(User::class)->first();
    }
    
    public function getAutoReports() {
        return $this->hasMany(AutoReportPool::class)->get();
    }
    
   
}
