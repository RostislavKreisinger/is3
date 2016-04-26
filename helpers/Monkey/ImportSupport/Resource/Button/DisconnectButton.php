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
class DisconnectButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                'Disconnect', 
                "https://import:vX1P8c@import.monkeydata.com/prehled/disconnectResource.php?project_id={$projectId}&resource_id={$resourceId}&client_id=1008"
                );
    }
    
}
