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
class B1_ResetAutomatTestButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B1_reset_automat_test',
                'Reset Automattest', 
                \Monkey\action(\App\Http\Controllers\Button\Resource\B1_ResetAutomatTestButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
        
        
    }
    
}
