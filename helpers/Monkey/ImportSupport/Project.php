<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport;

use App\Model\Project as ProjectModel;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

/**
 * Description of Project
 *
 * @method Project find($id, $columns = array()) 
 * @author Tomas
 */
class Project extends ProjectModel {
    
    
    /**
     *
     * @var Resource 
     */
    private $resources;
    
    
    
    /*
    public function __construct($project) {
        $this->setProject($project);
    }
    */
    /**
     * 
     * @return Resource[]
     */
    public function getProjectResources() {
        return $this->getResources();
    }
    
     /**
     * 
     * @return Resource[]
     */
    public function getResources() {
        if($this->resources === null){
            $data = parent::getResources()->where('allow_link', '!=', 0);
            $this->resources = array();
            foreach ($data->get() as $resource){
                $this->resources[$resource->id] = Resource::factory($resource, $this->id);
            }
        }
        return $this->resources;
    }
    
    
    public function getResource($resourceId) {
        return $this->getResources()[$resourceId];
    }
    
    
    
    /**
     * 
     * @return ProjectModel
     */
    public function getProject() {
        return $this;
    }
/*
    public function setProject($project) {
        if(!($project instanceof ProjectModel)){
            $project = ProjectModel::find($project);
        }
        $this->project = $project;
    }
    */
    
    public function getModel() {
        return $this;
    }
    
   
    public function isValid() {
        foreach($this->getResources() as $resource){
            if(!$resource->isValid()){
                return false;
            }
        }
        return true;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
