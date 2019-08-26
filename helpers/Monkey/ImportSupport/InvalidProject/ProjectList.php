<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\InvalidProject;

use Countable;
use Iterator;

/**
 * Description of ProjectList
 *
 * @author Tomas
 */
class ProjectList implements Iterator, Countable {

    private $position = 0;

    /**
     *
     * @var Project 
     */
    private $projects = array();
    
    /**
     *
     * @var int 
     */
    private $keys = array();

    public function __construct($data = array()) {
        foreach ($data as $row) {
            $project = $this->addProject(new Project($row->project_id, $row->project_name));
            $resource = new Resource($row->resource_id);
            $resource->setResourceSetting($row->resource_setting);
            $project->addResource($resource);
        }
    }

    public function getProjects() {
        return $this->projects;
    }

    public function setProjects(Project $projects) {
        $this->projects = $projects;
    }

    public function addProject(Project $project) {
        
        if(array_key_exists($project->getId(), $this->keys)){
            return $this->projects[$this->keys[$project->getId()]];
        }
        
        $this->projects[] = &$project;
        $this->keys[$project->getId()] = count($this->projects)-1;
        return $project;
        /*
        if(!isset($this->projects[$project->getId()])){
            $this->keys[] = $project->getId();
        }
        if(isset($this->projects[$project->getId()])){
            return $this->projects[$project->getId()];
        }
        $this->projects[$project->getId()] = &$project;
        return $project;
         */
    }
    
    public function getProject($projectId) {
        if( array_key_exists($projectId, $this->keys)){
            return $this->projects[$this->keys[$projectId]];
        }
        return null;
    }
    
    
    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->projects[ $this->position ];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->projects[$this->position]);
    }

    public function count($mode = 'COUNT_NORMAL') {
        return count($this->projects);
    }

}
