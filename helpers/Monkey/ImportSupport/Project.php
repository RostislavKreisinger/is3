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
 * @author Tomas
 */
class Project {
    
   


    /**
     *
     * @var ProjectModel
     */
    private $project;
    
    /**
     *
     * @var Resource 
     */
    private $resources;
    
    
    
    
    
    public function __construct($project) {
        $this->setProject($project);
    }
    
    public function getProjectResources() {
        if($this->resources === null){
            $data = $this->getProject()->getResources()->where('allow_link', '!=', 0);
            $this->resources = array();
            foreach ($data->get() as $resource){
                $this->resources[$resource->id] = Resource::factory($resource, $this->getProject()->id);
            }
        }
        return $this->resources;
    }
    
    
    
    /**
     * 
     * @return ProjectModel
     */
    public function getProject() {
        return $this->project;
    }

    public function setProject($project) {
        if(!($project instanceof ProjectModel)){
            $project = ProjectModel::find($project);
        }
        $this->project = $project;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
