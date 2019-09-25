<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource;

use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Resource;

/**
 * Description of ResourceV1
 *
 * @author Tomas
 */
class ResourceV1 extends Resource {

    public function __construct(array $attributes = array(), $project_id = null) {
        parent::__construct($attributes, $project_id);
    }

    public function getStateTester() {
        $resourceSetting = $this->getResourceStats()->getResourceSetting();
        if ($resourceSetting === null) {
            $resourceSetting = MDDatabaseConnections::getMasterAppConnection()
                    ->table('monkeydata.resource_setting')
                    ->select('*')
                    ->where('project_id', '=', $this->getProject_id())
                    ->where('resource_id', '=', $this->getResource()->id)
                    ->where('active', '!=', 3)
                    // ->whereRaw('COALESCE(`next_check_date`, 0) < NOW()')
                    ->first()
            ;
            $this->getResourceStats()->setResourceSetting($resourceSetting);
        }

        if (is_null($resourceSetting)) {
            return Resource::STATUS_ERROR;
        }

        if ($resourceSetting->active == 2 && $resourceSetting->ttl > 0) {
            return Resource::STATUS_RUNNING;
        }

        if ($resourceSetting->active == 0 && $resourceSetting->ttl > 0) {
            return Resource::STATUS_ACTIVE;
        }

        if ($resourceSetting->active == 1 && $resourceSetting->ttl > 0) {
            return Resource::STATUS_DONE;
        }

        return Resource::STATUS_ERROR;
    }

    public function getStateDaily() {
        $importPrepareNew = $this->getResourceStats()->getImportPrepareNew();

        if (is_null($importPrepareNew)) {
            return Resource::STATUS_MISSING_RECORD;
        }
        if ($importPrepareNew->ttl <= 0) {
            return Resource::STATUS_ERROR;
        }
        if ($importPrepareNew->ttl > 0) {
            if ($importPrepareNew->active == 1) {
                return Resource::STATUS_ACTIVE;
            }
            if ($importPrepareNew->active == 2) {
                return Resource::STATUS_RUNNING;
            }
        }

        return Resource::STATUS_ERROR;
    }

    public function getStateHistory() {
        $importPrepareStart = $this->getResourceStats()->getImportPrepareStart();

        if (is_null($importPrepareStart)) {
            return Resource::STATUS_MISSING_RECORD;
        }
        if ($importPrepareStart->ttl <= 0) {
            return Resource::STATUS_ERROR;
        }
        if ($importPrepareStart->ttl > 0) {
            if ($importPrepareStart->active == 1) {
                return Resource::STATUS_ACTIVE;
            }
            if ($importPrepareStart->active == 2) {
                return Resource::STATUS_RUNNING;
            }
        }

        if ($importPrepareStart->active == 0 && $importPrepareStart->ttl > 0 && $importPrepareStart->date_check == 1) {
            return Resource::STATUS_DONE;
        }


        return Resource::STATUS_ERROR;
    }

    public function getConnectionDetail() {
        $connectionDetail = array();

        $enableInfo = [
            'id' => function($value){ return $value;},
            'url' => function($value){ return $value;},
            
            'login' => function($value){ return $value;},
            'username' => function($value){ return $value;},
            
            'password' => function($value){ return decryptWithPrivate($value);},
            'access_token' => function($value){ return decryptWithPrivate($value);},
            
            'custom_setting' => function($value){ return $value;},
        ];
        foreach ((array)$this->getResourceDetail() as $key => $value) {
            if (in_array($key, array_keys($enableInfo)) && !is_null($value)) {
                $fnc = $enableInfo[$key];
                $connectionDetail[$key] = $fnc($value);
            }
        }
        return $connectionDetail;
    }

}
