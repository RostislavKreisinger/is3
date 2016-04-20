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


    
    public static function getAllInvalidProjects() {
        $builder = DB::connection('mysql-select')->table('monkeydata.resource_setting as rs')
                                ->select(['rs.project_id', 'rs.resource_id', DB::raw('IF(rs.active = 0 AND rs.ttl <= 0,0,1) as resource_setting')])
//                                ->where(function(Builder $where){
//                                    $where->orWhere(DB::raw('rs.active = 0 AND rs.ttl <= 0,0,1'), '=', 0);
//                                })
                ;
        vdQuery($builder);
        vde($builder->get());
        return $builder->get();
    }
    
    
}
