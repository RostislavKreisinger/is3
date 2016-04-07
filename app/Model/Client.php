<?php

namespace App\Model;

use Eloquent;

class Client extends Eloquent {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'client';
    
    protected $guarded = [];
    
    public function getProjects() {
        $projects = $this->hasMany(Project::class, 'user_id')
                ->get()
                ;
        return $projects;
        
    }

}
