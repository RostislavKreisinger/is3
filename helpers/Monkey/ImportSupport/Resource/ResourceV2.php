<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource;

use Monkey\ImportSupport\Resource;

/**
 * Description of ResourceV1
 *
 * @author Tomas
 */
class ResourceV2 extends Resource {
    
    public function getStateTester() {
        $resourceSetting = $this->getResourceStats()->getResourceSetting();
        if($resourceSetting === null){
            $resourceSetting = \DB::connection('mysql-select')
                                ->table('monkeydata.resource_setting')
                                ->select('*')
                                ->where('project_id', '=', $this->getProject_id())
                                ->where('resource_id', '=', $this->getResource()->id)
                                ->whereRaw('COALESCE(`next_check_date`, 0) < NOW()')
                                ->first()
                                ;
            $this->getResourceStats()->setResourceSetting($resourceSetting);
        }

        if(is_null($resourceSetting)){
            return Resource::STATUS_ERROR; 
        }
        
        if($resourceSetting->active == 2 && $resourceSetting->ttl > 0){
            return Resource::STATUS_RUNNING;
        }
        
        if($resourceSetting->active == 0 && $resourceSetting->ttl > 0){
            return Resource::STATUS_ACTIVE;
        }
        
        if($resourceSetting->active == 1 && $resourceSetting->ttl > 0){
            return Resource::STATUS_DONE;
        }
        
        return Resource::STATUS_ERROR;
    }
    
    public function getStateDaily() {
        $importPrepareNew = $this->getResourceStats()->getImportPrepareNew();
        if($importPrepareNew === null){
            $importPrepareNew = \DB::connection('mysql-select')
                                ->table('monkeydata_pools.import_prepare_new')
                                ->select('*')
                                ->where('project_id', '=', $this->getProject_id())
                                ->where('resource_id', '=', $this->getResource()->id)
                                ->first();
            $this->getResourceStats()->setImportPrepareNew($importPrepareNew);
        }
        if($this->isValidTester() && is_null($importPrepareNew)){
            return Resource::STATUS_ERROR;
        }
        if($importPrepareNew->ttl <= 0){
            return Resource::STATUS_ERROR;
        }
        if( $importPrepareNew->ttl > 0 ){
            if($importPrepareNew->active == 1){
                return Resource::STATUS_ACTIVE;
            }
            if($importPrepareNew->active == 2){
                return Resource::STATUS_RUNNING;
            }
        }
        
        return Resource::STATUS_ERROR;
    }
    
    
    public function getStateHistory() {
        $importPrepareStart = $this->getResourceStats()->getImportPrepareNew();
        if($importPrepareStart === null){
            $importPrepareStart = \DB::connection('mysql-select')
                                ->table('monkeydata_pools.import_prepare_start')
                                ->select(['*', \DB::raw('IF(date_to <= date_from, 1, 0) as date_check ') ])
                                ->where('project_id', '=', $this->getProject_id())
                                ->where('resource_id', '=', $this->getResource()->id)
                                ->first();
            $this->getResourceStats()->setImportPrepareNew($importPrepareStart);
        }
        
        if($this->isValidTester() && is_null($importPrepareStart)){
            return Resource::STATUS_ERROR;
        }
        if($importPrepareStart->ttl <= 0){
            return Resource::STATUS_ERROR;
        }
        if( $importPrepareStart->ttl > 0 ){
            if($importPrepareStart->active == 1){
                return Resource::STATUS_ACTIVE;
            }
            if($importPrepareStart->active == 2){
                return Resource::STATUS_RUNNING;
            }
        }
        
        if($importPrepareStart->active == 0 && $importPrepareStart->ttl > 0 && $importPrepareStart->date_check == 1){
            return Resource::STATUS_DONE;
        }
        
        
        return Resource::STATUS_ERROR;
    }
    
}