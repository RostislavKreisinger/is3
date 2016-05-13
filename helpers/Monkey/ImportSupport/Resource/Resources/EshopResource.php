<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Resources;

use Illuminate\Support\Facades\Auth;
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
        $EshopDeleteDataButton = new EshopDeleteDataButton($this->getProject_id(), $this->id);
        $UpdateOrdersButton = new UpdateOrdersButton($this->getProject_id(), $this->id);
        if (Auth::user()->can('project.resource.button.delete.delete_data')) {
            $this->addButton($EshopDeleteDataButton);
        }
        if (Auth::user()->can('project.resource.button.repair.update_orders')) {
            $this->addButton($UpdateOrdersButton);
        }
        parent::addDefaultButtons();
    }

}
