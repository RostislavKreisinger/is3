<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\InvalidProject;
use Monkey\Connections\MDDatabaseConnections;

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

    /**
     * @param Resource $resource
     * @return Resource
     */
    public function addResource(Resource $resource) {
        if($resource->getId() == 4 ){
            $eshopType = MDDatabaseConnections::getMasterAppConnection()
                ->table('resource_setting as rs')
                ->join('resource_eshop as re', 're.resource_setting_id', '=', 'rs.id')
                ->join('eshop_type as et', 'et.id', '=', 're.eshop_type_id')
                ->where('rs.project_id', '=', $this->getId())
                ->where('rs.resource_id', '=', $resource->getId())
                ->select('et.*')->first();
            if($eshopType->import_version == 2){
                $this->resources[$resource->getId()] = &$resource;
                return $resource;
            }
        }else{
            $dbResource = MDDatabaseConnections::getMasterAppConnection()
                ->table('resource as r')
                ->where('id', '=', $resource->getId())
                ->select('r.*')->first();
            if($dbResource->import_version == 2){
                $this->resources[$resource->getId()] = &$resource;
                return $resource;
            }
        }





    }


    
}
