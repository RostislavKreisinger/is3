<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

use \DateTime;


/**
 * Description of ShowButton
 *
 * @author Tomas
 */
class AutomatTestButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        $date = (new DateTime())->format('Y-m-d');
        parent::__construct(
                'Automat test', 
                "https://import.monkeydata.com/importgoogle.monkeydata.cz/import_prepare_manual.php?project_id={$projectId}&resource_id={$resourceId}&date={$date}&reset=1"
                );
    }
    
}
