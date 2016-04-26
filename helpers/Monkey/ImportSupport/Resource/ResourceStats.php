<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource;

/**
 * Description of ResourceStats
 *
 * @author Tomas
 */
class ResourceStats {
    
    private $resourceSetting;
    
    private $importPrepareStart;
    
    private $importPrepareNew;
    
    
    
    
    public function getResourceSetting() {
        return $this->resourceSetting;
    }

    public function getImportPrepareStart() {
        return $this->importPrepareStart;
    }

    public function getImportPrepareNew() {
        return $this->importPrepareNew;
    }

    public function setResourceSetting($resourceSetting) {
        $this->resourceSetting = $resourceSetting;
    }

    public function setImportPrepareStart($importPrepareStart) {
        $this->importPrepareStart = $importPrepareStart;
    }

    public function setImportPrepareNew($importPrepareNew) {
        $this->importPrepareNew = $importPrepareNew;
    }


}
