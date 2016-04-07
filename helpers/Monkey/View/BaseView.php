<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View;

use Exception;
// use Illuminate\Support\Contracts\RenderableInterface as Renderable;
/**
 * Description of BaseView
 *
 * @author Tomas
 */
abstract class BaseView implements \Illuminate\Contracts\Support\Renderable {

    public static $sharedParrameters = array();
    
    private $parameters = array();
    
    protected function __construct() {
    }
    
    public static function share($name, $value) {
        static::$sharedParrameters[$name] = $value;
    }



    public function with($name, $value) {
        $this->addParameter($name, $value);
    }

    public function getParameters() {
        return $this->parameters;
    }
    
    public function getParametersToView() {
        $preparedParameters = array();
        foreach($this->getParameters() as $key => $parametr){
            if($parametr instanceof BaseView){
                $preparedParameters[$key] = $parametr; //->render();
            }else{
                $preparedParameters[$key] = $parametr;
            }
        }
        return array_merge(static::$sharedParrameters, $preparedParameters, array('_parrams' => $this->getParameters()));
    }

    public function addParameter($name, $value) {
        // $this->parameters['__parent'] = $value;
        $this->parameters[$name] = $value;
        return $this;
    }

    protected function setParameters($parameters) {
        $this->parameters = $parameters;
        return $this;
    }

     public function __set($name, $value) {
        $this->addParameter($name, $value);
    }

    public function __get($name) {
        if(!isset($this->parameters[$name])){
            throw new Exception("Undefined parameter '{$name}' ");
        }
        return $this->parameters[$name];
    }
}
