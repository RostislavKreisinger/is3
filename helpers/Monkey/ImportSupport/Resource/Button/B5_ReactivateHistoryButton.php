<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

use App\Http\Controllers\Button\Resource\B5_ReactivateHistoryButtonController;
/**
 * Description of ShowButton
 *
 * @author Tomas
 */
class B5_ReactivateHistoryButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B5_reactivate_history',
                'Reactivate History', 
                \Monkey\action(B5_ReactivateHistoryButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
        $this->setToNewWindow(false);
        $this->setTitle('Nastavi pouze active na 1 a ttl na 5, aby se zacalo stahovat standartnim procesem.');
        
    }
    
}
