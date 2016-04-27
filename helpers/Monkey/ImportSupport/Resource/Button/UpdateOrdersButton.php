<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;


/**
 * Description of ShowButton
 *
 * @author Tomas
 */
class DeleteDataButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                'update_orders',
                'Update orders', 
                "https://import-support.monkeydata.com/resource/update-order-updated-at/{$projectId}{$resourceId}"
                );
    }
    
}
