<?php

namespace App\Model;

use Monkey\ImportSupport\Project as ImportSupportProject;

class User extends Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'user';
    
    protected $guarded = [];
    
    
    /**
     * 
     * @return ImportSupportProject[]
     */
    public function getProjects() {
        $projects = $this->hasMany(ImportSupportProject::class, 'user_id');
        return $projects;
        
    }
    
    /**
     * 
     * @return Client
     */
    public function getClient() {
        return $this->hasMany(Client::class, 'user_id')->first();
    }

}
