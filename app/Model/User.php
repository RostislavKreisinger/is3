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
     * @var ImportSupportProject 
     */
    protected $projects;
    
    /**
     *
     * @var Client 
     */
    protected $client;
    
    
    /**
     * 
     * @return ImportSupportProject[]
     */
    public function getProjects() {
        if($this->projects === null){
            $this->projects = $this->builderProjects()->get();
        }
        return $this->projects;
    }
    
    public function builderProjects() {
        return $this->hasMany(ImportSupportProject::class, 'user_id');
    }
    
    /**
     * 
     * @return Client
     */
    public function getClient() {
        if($this->client === null){
            $this->client = $this->hasMany(Client::class, 'user_id')->first();
        }
        return $this->client;
    }
    
}
