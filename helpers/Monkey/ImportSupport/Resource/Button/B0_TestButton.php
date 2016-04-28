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
class B0_TestButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B0_test',
                'Test', 
                "https://import.monkeydata.com/importgoogle.monkeydata.cz/import_support_b0.php?project_id={$projectId}&resource_id={$resourceId}"
                );
    }
    
}
