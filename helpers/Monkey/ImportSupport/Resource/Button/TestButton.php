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
class TestButton extends BaseButton {
    
    public function __construct() {
        parent::__construct(
                'Test', 
                '#'
                );
    }
    
}