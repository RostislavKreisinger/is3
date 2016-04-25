<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

/**
 * Description of Button
 *
 * @author Tomas
 */
class BaseButton {
    
    private $url;
    
    private $name;
    
    public function __construct($name, $url) {
        
    }
    
    public function getUrl() {
        return $this->url;
    }

    public function getName() {
        return $this->name;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setName($name) {
        $this->name = $name;
    }


    
}
