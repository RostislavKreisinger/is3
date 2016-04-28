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
    
    const BUTTON_TYPE_TEST = 'test';
    const BUTTON_TYPE_REPAIR = 'repair';
    const BUTTON_TYPE_DELETE = 'delete';
    
    private $type = self::BUTTON_TYPE_TEST;
    
    private $code;
    
    private $url;
    
    private $name;
    
    private $class = 'btn-default';
    
    private $title;
    
    private $error = false;
    
    public function __construct($type, $code, $name, $url) {
        $this->setType($type);
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

    public function getType() {
        return $this->type;
    }
    
    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }
    
    public function isDisabled() {
        return $this->error !== false;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    
    
    public function setType($type) {
        $this->type = $type;
        switch ($type){
            case self::BUTTON_TYPE_TEST: 
                $this->setClass('btn-default');
                break;
            case self::BUTTON_TYPE_REPAIR: 
                $this->setClass('btn-info');
                break;
            case self::BUTTON_TYPE_DELETE: 
                $this->setClass('btn-danger');
                break;
        }
    }


    
}
