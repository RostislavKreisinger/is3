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
class Resource {
   
    /**
     *
     * @var int 
     */
    private $id;
    
    /**
     *
     * @var int 
     */
    private $resourceSetting;
    
    
    /**
     *
     * @var int 
     */
    private $importPrepareNew;
    
    /**
     *
     * @var int 
     */
    private $importPrepareStart;
    
    public function __construct($id) {
        $this->setId($id);
    }
    
    public function getId() {
        return $this->id;
    }

    public function getResourceSetting() {
        return $this->resourceSetting;
    }

    public function getImportPrepareNew() {
        return $this->importPrepareNew;
    }

    public function getImportPrepareStart() {
        return $this->importPrepareStart;
    }

    public function setId($resourceId) {
        $this->id = $resourceId;
    }

    public function setResourceSetting($resourceSetting) {
        $this->resourceSetting = $resourceSetting;
    }

    public function setImportPrepareNew($importPrepareNew) {
        $this->importPrepareNew = $importPrepareNew;
    }

    public function setImportPrepareStart($importPrepareStart) {
        $this->importPrepareStart = $importPrepareStart;
    }


}
