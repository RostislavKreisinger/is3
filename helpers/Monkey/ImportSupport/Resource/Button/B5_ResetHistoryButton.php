<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

use App\Http\Controllers\Button\Resource\B5_ResetHistoryButtonController;
/**
 * Description of ShowButton
 *
 * @author Tomas
 */
class B5_ResetHistoryButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B5_reset_history',
                'Reset History', 
                \Monkey\action(B5_ResetHistoryButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
        $this->setToNewWindow(false);
    }
    
}
