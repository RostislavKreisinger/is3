<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button\Other;

use Monkey\ImportSupport\Resource\Button\BaseButton;

/**
 * Description of UnconnectButton
 *
 * @author Tomas
 */
class UnconnectButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_DELETE,
                'unconnect',
                'Unconnect', 
                \Monkey\action(\App\Http\Controllers\Button\Resource\Other\UnconnectButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
        $this->setToNewWindow(false);
    }
}
