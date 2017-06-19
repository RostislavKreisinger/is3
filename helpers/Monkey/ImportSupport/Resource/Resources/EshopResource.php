<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Resources;

use App\Model\EshopType;
use Illuminate\Support\Facades\Auth;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Resource;
use Monkey\ImportSupport\Resource\Button\Other\EshopDeleteDataButton;
use Monkey\ImportSupport\Resource\Button\Other\UpdateOrdersButton;
use Monkey\ImportSupport\Resource\ResourceV2;

/**
 * Description of EshopResource
 *
 * @author Tomas
 */
class EshopResource extends ResourceV2 {

    protected function addDefaultButtons() {
        if(!$this->isImportFlowEshop()) {
            $EshopDeleteDataButton = new EshopDeleteDataButton($this->getProject_id(), $this->id);
            $UpdateOrdersButton = new UpdateOrdersButton($this->getProject_id(), $this->id);
            if (Auth::user()->can('project.resource.button.delete.delete_data')) {
                $this->addButton($EshopDeleteDataButton);
            }
            if (Auth::user()->can('project.resource.button.repair.update_orders')) {
                $this->addButton($UpdateOrdersButton);
            }
            parent::addDefaultButtons();
        } else {
            $this->addShowDataButton();
        }
    }

    private function isImportFlowEshop() {
        $eshopType = EshopType::find($this->getResourceDetail()->eshop_type_id);
        return ($eshopType->import_version == 3);
    }



    public function getStateTester() {
        $result = parent::getStateTester();
        if($this->isImportFlowEshop()){
            return Resource::STATUS_DEACTIVE;
        }
        return $result;
    }

    public function getStateDaily() {
        if($this->isImportFlowEshop()){
            return Resource::STATUS_DEACTIVE;
        }
        return parent::getStateDaily();
    }

    public function getStateDailyImportFlow() {
        if($this->isImportFlowEshop()){
            // return Resource::STATUS_DEACTIVE;
            return $this->_getStateDailyImportFlow();
        }
        return parent::getStateDailyImportFlow();
    }

    public function getStateHistoryImportFlow() {
        if($this->isImportFlowEshop()){
            // return Resource::STATUS_DEACTIVE;
            return $this->_getStateHistoryImportFlow();
        }
        return parent::getStateHistoryImportFlow();
    }


    private function _getStateDailyImportFlow() {
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

    private function _getStateHistoryImportFlow() {
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
        if($this->isImportFlowEshop()){
            return Resource::STATUS_DEACTIVE;
        }
        return parent::getStateHistory();
    }

}
