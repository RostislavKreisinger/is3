<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\InvalidProject;

/**
 * Description of Project
 *
 * @author Tomas
 */
class Project {
   
    /**
     *
     * @var int 
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    private $name;
    
    
    
    private $resources = array();
    
    public function __construct($project_id, $project_name) {
        $this->setId($project_id);
        $this->setName($project_name);        
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function getInvalidResourceCount() {
        return count($this->getResources());
    }
    
    public function getResourceModels() {
        $resourceList = ProjectRepository::getResourceList();
        return array_map(function(Resource $resource) use ($resourceList) { return $resourceList[$resource->getId()]; }, $this->getResources());
    }
    

    /**
     * 
     * @return Resource
     */
    public function getResources() {
        return $this->resources;
    }


    public function setResources($resources) {
        $this->resources = $resources;
    }
    
    public function addResource(Resource $resource) {
        $this->resources[$resource->getId()] = &$resource;
        return $resource;
    }


    
}
