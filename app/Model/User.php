<?php

namespace App\Model;

use Eloquent;

class User extends Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'user';
    
    protected $guarded = [];
    
    
    /**
     * 
     * @return Project[]
     */
    public function getProjects() {
        $projects = $this->hasMany(\Monkey\ImportSupport\Project::class, 'user_id');
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
