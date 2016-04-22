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
            $resource->setImportPrepareNew($row->import_prepare_new);
            $resource->setImportPrepareStart($row->import_prepare_start);
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
        if(!isset($this->projects[$project->getId()])){
            $this->keys[] = $project->getId();
        }
        $this->projects[$project->getId()] = &$project;
        return $project;
    }
    
    
    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->projects[ $this->keys[$this->position] ];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->keys[$this->position]) && isset( $this->projects[ $this->keys[$this->position] ] );
    }

    public function count($mode = 'COUNT_NORMAL') {
        return count($this->projects);
    }

}
