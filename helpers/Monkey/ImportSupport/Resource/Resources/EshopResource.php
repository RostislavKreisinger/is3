<?php

namespace Monkey\ImportSupport\Resource\Resources;


use App\Model\EshopType;
use Illuminate\Support\Facades\Auth;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Resource;
use Monkey\ImportSupport\Resource\Button\Other\EshopDeleteDataButton;
use Monkey\ImportSupport\Resource\Button\Other\UpdateOrdersButton;
use Monkey\ImportSupport\Resource\ResourceV3;

/**
 * Description of EshopResource
 *
 * @author Tomas
 */
class EshopResource extends ResourceV3 {

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

    /**
     * @return bool
     */
    private function isImportFlowEshop() {
        $resourceDetail = $this->getResourceDetail();

        if (is_null($resourceDetail)) {
            return false;
        }

        $eshopType = EshopType::find($resourceDetail->eshop_type_id);
        return $eshopType->import_version == 3;
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

    public function getStateHistory() {
        if($this->isImportFlowEshop()){
            return Resource::STATUS_DEACTIVE;
        }
        return parent::getStateHistory();
    }

}
