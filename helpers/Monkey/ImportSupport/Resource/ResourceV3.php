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
class ResourceV3 extends Resource {

    public function __construct(array $attributes = array(), $project_id = null) {
        parent::__construct($attributes, $project_id);
    }

    public function getStateTester() {
        $resourceSetting = $this->getResourceStats()->getResourceSetting();
        if ($resourceSetting === null) {
            $resourceSetting = MDDatabaseConnections::getMasterAppConnection()
                ->table('monkeydata.'.Resource::RESOURCE_SETTING)
                ->select('*')
                ->where('project_id', '=', $this->getProject_id())
                ->where('resource_id', '=', $this->getResource()->id)
                ->where('active', '!=', 3)
                // ->whereRaw('COALESCE(`next_check_date`, 0) < NOW()')
                ->first()
            ;
            $this->getResourceStats()->setResourceSetting($resourceSetting);
        }


        return Resource::STATUS_ACTIVE;
        return Resource::STATUS_MISSING_RECORD;
    }

    public function getStateDaily() {
        return Resource::STATUS_ACTIVE;
        return Resource::STATUS_MISSING_RECORD;
    }

    public function getStateDailyImportFlow() {
        return Resource::STATUS_ACTIVE;
        return Resource::STATUS_MISSING_RECORD;
    }

    public function getStateHistoryImportFlow() {
        return Resource::STATUS_ACTIVE;
        return Resource::STATUS_MISSING_RECORD;
    }


    public function getStateHistory() {
        return Resource::STATUS_ACTIVE;
        return Resource::STATUS_MISSING_RECORD;
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
        foreach ($this->getResourceDetail() as $key => $value) {
            if (in_array($key, array_keys($enableInfo)) && !is_null($value)) {
                $fnc = $enableInfo[$key];
                $connectionDetail[$key] = $fnc($value);
            }
        }
        return $connectionDetail;
    }

    protected function addDefaultButtons() {
        $this->addShowDataButton();
    }

}
