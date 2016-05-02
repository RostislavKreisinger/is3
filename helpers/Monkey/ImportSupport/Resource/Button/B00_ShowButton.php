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
class B00_ShowButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                BaseButton::BUTTON_TYPE_TEST,
                'B00_show_data',
                'Show data', 
                \Monkey\action(\App\Http\Controllers\Database\DatabaseSelectorController::routeMethod('postIndex'), ['project_id' => $projectId, 'resource_id' => $resourceId ])
                );
    }
    
}
