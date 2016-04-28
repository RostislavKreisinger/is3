<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

use App\Http\Controllers\Button\Resource\B6_ResetDailyButtonController;
/**
 * Description of ShowButton
 *
 * @author Tomas
 */
class B6_ResetDailyButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B6_reset_daily',
                'Reset Daily', 
                \Monkey\action(B6_ResetDailyButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
    }
    
}
