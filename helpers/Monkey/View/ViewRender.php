<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Monkey\View\View;



/**
 * Description of ViewHelper
 *
 * @author Tomas
 */
class ViewRender {

    static $instance = null;
    
    
    private $layoutsBasePath = 'default';
    private $defaultLayoutName = 'index';
    private $baseLayout = null;
    
    private $layout = null;
    private $body = null;
    private $head = null;
    
    private $errors = array();
    private $messages = array();

    protected function __construct($route) {
        $this->initView($route);
    }
  
    protected function initView($route) {
        $route = $this->initRoute($route);
        $htmlPath = $this->findHtml($route);
        $this->baseLayout = new View($htmlPath);//  new View($this->getViewName('@html'));
        $this->baseLayout->addParameter('errors', $this->errors);
        $this->baseLayout->addParameter('messages', $this->messages);
       
        $layoutPath = $this->findLayout($route);
        $this->layout = new View($layoutPath);
        $this->layout->addParameter('errors', $this->errors);
        $this->layout->addParameter('messages', $this->messages);
        $this->baseLayout->addParameter('layout', $this->layout);
        
        
        $headPath = $this->findHead($route);
        $this->head = new View($headPath);
        $this->head->addParameter('errors', $this->errors);
        $this->head->addParameter('messages', $this->messages);
        $this->baseLayout->addParameter('head', $this->head);
        
        $this->body = new View($route);
        $this->body->addParameter('errors', $this->errors);
        $this->body->addParameter('messages', $this->messages);
        $this->layout->addParameter('body', $this->body);
         
    }
    
    protected function initRoute($route) {
        $templateName = array_merge([$this->layoutsBasePath], $route);
        foreach ($templateName as $key => $value){
            if(empty($value)){
                unset($templateName[$key]);
            }
        }
        return $templateName;
    }

    /**
     * 
     * @return ViewRender
     */
    public static function getInstance($route = null) {
        if (static::$instance === null) {
            static::$instance = new ViewRender($route);
        }
        return static::$instance;
    }

    public static function setLayoutTeplate($template) {
        $object = static::getInstance();
        $object->layout->setTemplate($template);
    }

    public static function addParameter($name, $parameter) {
        $object = static::getInstance();
        $object->body->addParameter($name, $parameter);
    }
    
    public static function addView($name, $template, $parameters = array()) {
        $object = static::getInstance();
        $tmp = new View($object->getViewName($template), $parameters);
        $object->layout->addParameter($name, $tmp);
        return $tmp;
    }
    
    public static function addLayoutParameter($name, $parameter) {
        $object = static::getInstance();
        $object->layout->addParameter($name, $parameter);
    }

    protected function getViewPath($viewName) {
        if (View::exists($this->getLayoutsBasePath() . "." . $this->getLayoutName() . "." . $viewName)) {
            return $this->getLayoutsBasePath() . "." . $this->getLayoutName() . "." . $viewName;
        }
        if (View::exists($this->getLayoutsBasePath() . "." . $this->getDefaultLayoutName() . "." . $viewName)) {
            return $this->getLayoutsBasePath() . "." . $this->getDefaultLayoutName() . "." . $viewName;
        }
        throw new Exception("Desired view does not exist ('{$viewName}')");
    }
    
    protected function findView($route, $viewName) {
        $tmpRoute = array_values( $route );
        unset($tmpRoute[count($tmpRoute) - 1]);
        $count = count($tmpRoute);
        while ($count >= 0) {
            if (ViewFinder::existView(implode('.', $tmpRoute) . "." . $viewName)) {
                return array_merge($tmpRoute, [$viewName]);
            }
            unset($tmpRoute[count($tmpRoute) - 1]);
            $count--;
        }
        return false;
    }

    protected function findLayout($route) {
        return $this->findView($route, '@layout');
    }
    
    protected function findHtml($route) {
        return $this->findView($route, '@html');
    }
    
    protected function findHead($route) {
        return $this->findView($route, '@head');
    }

    public function render() {
        return $this->getRenderedView();
    }

    protected function getRenderedView() {
        return $this->baseLayout->render();
    }

    protected function getLayoutsBasePath() {
        return $this->layoutsBasePath;
    }

    protected function getDefaultLayoutName() {
        return $this->defaultLayoutName;
    }

    protected function getLayoutName() {
        return $this->layoutName;
    }
    
    public function setBody($view) {
        if($view instanceof View){
            $this->body = $view;
        }
        $this->body = new View($view);
        $this->layout->addParameter('body', $this->body);
        
    }
    
    protected function getViewName($name, $path = array()) {
        if(!is_array($path)){
            $path = explode('.', $path);
        }
        if(!is_array($name)){
            $name = explode('.', $name);
        }
        return array_merge([$this->layoutsBasePath], $path, $name);
    }

}
