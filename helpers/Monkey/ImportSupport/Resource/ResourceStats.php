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

    private $importFlowDaily;

    private $importFlowHistory;

    
    
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

    /**
     * @return mixed
     */
    public function getImportFlowDaily() {
        return $this->importFlowDaily;
    }

    /**
     * @param mixed $importFlowDaily
     * @return ResourceStats
     */
    public function setImportFlowDaily($importFlowDaily) {
        $this->importFlowDaily = $importFlowDaily;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImportFlowHistory() {
        return $this->importFlowHistory;
    }

    /**
     * @param mixed $importFlowHistory
     * @return ResourceStats
     */
    public function setImportFlowHistory($importFlowHistory) {
        $this->importFlowHistory = $importFlowHistory;
        return $this;
    }




}
