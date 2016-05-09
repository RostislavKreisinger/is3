<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Resources;

use Monkey\ImportSupport\Resource\Button\Other\EshopDeleteDataButton;
use Monkey\ImportSupport\Resource\ResourceV2;



/**
 * Description of EshopResource
 *
 * @author Tomas
 */
class EshopResource extends ResourceV2 {
    
    
    
    
    protected function addDefaultButtons() {
        $EshopDeleteDataButton = new EshopDeleteDataButton($this->getProject_id(), $this->id);
        $this->addButton($EshopDeleteDataButton);
        parent::addDefaultButtons();
    }
}
