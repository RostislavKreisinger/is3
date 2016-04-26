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
                'Automat test', 
                "https://import.monkeydata.com/importgoogle.monkeydata.cz/import-support-extra-save-functions/cleanEshopDataLoader.php?project_id={$projectId}&client_id=1008"
                );
    }
    
}
