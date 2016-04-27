<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

use Monkey\View\View;

/**
 * Description of Button
 *
 * @author Tomas
 */
class BaseButton {
    
    private $code;
    
    private $url;
    
    private $name;
    
    private $class = 'btn-default';
    
    public function __construct($code, $name, $url) {
        $this->setCode($code);
        $this->setName($name);
        $this->setUrl($url);
    }
    
    public function getCode() {
        return $this->code;
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

    public function getClass() {
        return $this->class;
    }

    public function addClass($class) {
        $this->class .= ' '.$class;
    }
    
    public function setClass($class) {
        $this->class = $class;
    }
    
    protected function setCode($code) {
        $this->code = $code;
    }

    
    public function getView() {
        $view = new View('default.project.resource.template.button');
        $view->addParameter('button', $this);
        
        return $view;
    }

    
}
