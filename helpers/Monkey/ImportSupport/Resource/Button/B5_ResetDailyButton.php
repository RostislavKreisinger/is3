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
class B5_ResetDailyButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B5_reset_daily',
                'Reset Daily', 
                "#"
                );
    }
    
}
