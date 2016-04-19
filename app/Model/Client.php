<?php

namespace App\Model;

use Eloquent;

class Client extends Eloquent {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'client';
    
    protected $guarded = [];
    
    /**
     * 
     * @return Project[]
     */
    public function getProjects() {
        $projects = $this->hasMany(Project::class, 'user_id');
        return $projects;
        
    }
    
    /**
     * 
     * @return Tariff
     */
    public function getTariff() {
        return Tariff::find($this->tariff_id);
    }

}
