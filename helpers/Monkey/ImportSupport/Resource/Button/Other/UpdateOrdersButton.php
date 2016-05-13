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
class UpdateOrdersButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_REPAIR,
                'update-orders',
                'Update orders', 
                \Monkey\action(\App\Http\Controllers\Button\Resource\Other\UpdateOrdersButtonController::class, ['project_id' => $projectId, 'resource_id' => $resourceId])
                );
        $this->setToNewWindow(false);
        $this->setTitle('Provede update create_at vsech objednavek aby se vsechny v nasledujicim stazeni opravili.');
    }
}
