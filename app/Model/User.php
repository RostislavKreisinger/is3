<?php

namespace App\Model;

use Eloquent;

class User extends Eloquent {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'user';
    
    protected $guarded = [];
    
    public function getProjects() {
        $projects = $this->hasMany(Project::class, 'user_id')
                ->get()
                ;
        return $projects;
        
    }
    
    public function getClient() {
        $client = $this->hasMany(Client::class, 'user_id')
                ->first()
                ;
        return $client;
        
    }

}
