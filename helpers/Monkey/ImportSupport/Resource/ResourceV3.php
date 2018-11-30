<?php

namespace Monkey\ImportSupport\Resource;


use App\Model\ImportPools\IFDailyPool;
use App\Model\ImportPools\IFHistoryPool;
use App\Model\ResourceSetting;
use DB;
use Monkey\ImportSupport\Resource;

/**
 * Description of ResourceV1
 *
 * @author Tomas
 */
class ResourceV3 extends Resource {
    /**
     * @return bool|string
     */
    public function getStateTester() {
        $resourceSetting = $this->getResourceStats()->getResourceSetting();

        if ($resourceSetting === null) {
            $resourceSetting = ResourceSetting::where('project_id', $this->getProject_id())
                ->where('resource_id', $this->getResource()->id)->first();
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
        return Resource::STATUS_ACTIVE;
    }

    public function getStateDailyImportFlow() {
        $importFlowDaily = $this->getResourceStats()->getImportFlowDaily();

        if ($importFlowDaily === null) {
            $importFlowDaily = IFDailyPool::query()
                ->where('project_id', $this->getProject_id())
                ->where('resource_id', $this->id)
                ->first(['id', 'active', 'ttl', 'next_run_date', 'start_at', 'finish_at', 'if_import_id']);
            $this->getResourceStats()->setImportFlowDaily($importFlowDaily);
        }

        if (is_null($importFlowDaily)) {
            return Resource::STATUS_MISSING_RECORD;
        }

        if ($importFlowDaily->ttl <= 0) {
            return Resource::STATUS_ERROR;
        }

        if ($importFlowDaily->ttl > 0) {
            switch ($importFlowDaily->active) {
                case 0:
                    return Resource::STATUS_INACTIVE;
                case 1:
                    return Resource::STATUS_ACTIVE;
                case 2:
                case 5:
                    return Resource::STATUS_RUNNING;
            }
        }

        return Resource::STATUS_ERROR;
    }

    public function getStateHistoryImportFlow() {
        $importFlowHistory = $this->getResourceStats()->getImportFlowHistory();

        if ($importFlowHistory === null) {
            $importFlowHistory = IFHistoryPool::query()
                ->where('project_id', $this->getProject_id())
                ->where('resource_id', $this->id)
                ->first([
                    'id',
                    'active',
                    'ttl',
                    'start_at',
                    'finish_at',
                    'date_from',
                    'date_to',
                    DB::raw('IF(date_to <= date_from, 1, 0) as date_check'),
                    'if_import_id'
                ]);
            $this->getResourceStats()->setImportFlowHistory($importFlowHistory);
        }

        if (is_null($importFlowHistory)) {
            return Resource::STATUS_MISSING_RECORD;
        }

        if ($importFlowHistory->ttl <= 0) {
            return Resource::STATUS_ERROR;
        }

        if ($importFlowHistory->ttl > 0) {
            switch ($importFlowHistory->active) {
                case 0:
                    if ($importFlowHistory->date_check == 1) {
                        return Resource::STATUS_DONE;
                    }

                    return Resource::STATUS_INACTIVE;
                case 1:
                    return Resource::STATUS_ACTIVE;
                case 2:
                case 5:
                    return Resource::STATUS_RUNNING;
            }
        }

        return Resource::STATUS_ERROR;
    }

    public function getStateHistory() {
        return Resource::STATUS_ACTIVE;
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