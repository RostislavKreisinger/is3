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
                // ->where('active', '!=', 3)
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
        $importFlowDaily = $this->getResourceStats()->getImportFlowDaily();
        if ($importFlowDaily === null) {
            $importFlowDaily = MDDatabaseConnections::getImportFlowConnection()
                ->table('if_daily as ifd')
                ->leftJoin('if_import as ifi', 'ifi.id', '=','ifd.if_import_id')
                ->select(['ifd.id','ifd.active', 'ifd.ttl', 'ifi.unique','ifd.next_run_date', 'ifd.start_at', 'ifd.finish_at'])
                ->where('ifd.project_id', '=', $this->getProject_id())
                ->where('ifd.resource_id', '=', $this->getResource()->id)
                ->first();
            $this->getResourceStats()->setImportFlowDaily($importFlowDaily);
        }

        if (is_null($importFlowDaily)) {
            return Resource::STATUS_MISSING_RECORD;
        }
        if ($importFlowDaily->ttl <= 0) {
            return Resource::STATUS_ERROR;
        }
        if ($importFlowDaily->ttl > 0) {
            if ($importFlowDaily->active == 1) {
                return Resource::STATUS_ACTIVE;
            }
            if ($importFlowDaily->active == 2) {
                return Resource::STATUS_RUNNING;
            }
        }

        return Resource::STATUS_ERROR;
    }

    public function getStateHistoryImportFlow() {
        $importFlowHistory = $this->getResourceStats()->getImportFlowHistory();
        if ($importFlowHistory === null) {
            $importFlowHistory = MDDatabaseConnections::getImportFlowConnection()
                ->table('if_history as ifh')
                ->leftJoin('if_import as ifi', 'ifi.id', '=','ifh.if_import_id')
                ->select(['ifh.id','ifh.active', 'ifh.ttl', 'ifi.unique', 'ifh.start_at', 'ifh.finish_at', 'ifh.date_from', 'ifh.date_to', \DB::raw('IF(ifh.date_to <= ifh.date_from, 1, 0) as date_check')])
                ->where('ifh.project_id', '=', $this->getProject_id())
                ->where('ifh.resource_id', '=', $this->getResource()->id)
                ->first();
            $this->getResourceStats()->setImportFlowHistory($importFlowHistory);

        }

        if (is_null($importFlowHistory)) {
            return Resource::STATUS_MISSING_RECORD;
        }
        if ($importFlowHistory->ttl <= 0) {
            return Resource::STATUS_ERROR;
        }
        if ($importFlowHistory->ttl > 0) {
            if ($importFlowHistory->active == 1) {
                return Resource::STATUS_ACTIVE;
            }
            if ($importFlowHistory->active == 2) {
                return Resource::STATUS_RUNNING;
            }
        }

        if ($importFlowHistory->active == 0 && $importFlowHistory->ttl > 0 && $importFlowHistory->date_check == 1) {
            return Resource::STATUS_DONE;
        }

        return Resource::STATUS_ERROR;
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
        foreach ((array)$this->getResourceDetail() as $key => $value) {
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
