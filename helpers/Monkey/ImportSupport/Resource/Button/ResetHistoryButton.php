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
class ResetHistoryButton extends BaseButton {
    
    public function __construct($projectId, $resourceId) {
        parent::__construct(
                'reset_history',
                'Reset history', 
                "https://import-support.monkeydata.com/resource/reset-date-ttl/{$projectId}/{$resourceId}"
                );
    }
    
}
