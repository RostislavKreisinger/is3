<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\View;

use Illuminate\Filesystem\Filesystem;

/**
 * Description of ViewFinder
 *
 * @author Tomas
 */
class ViewFinder {
    
    static $instance = null;
    
    
    private function __construct() {
    }
    
    /**
     * 
     * @return Filesystem
     */
    public static function getInstance() {
        if(static::$instance === null){
            static::$instance = new Filesystem();
        }    
        return static::$instance;
    }
    
    
    public static function existFile($path) {
        $object = static::getInstance();
        return $object->exists($path);
    }
    
    public static function existView($name){
        if(is_array($name)){
            $name = implode('.', $name);
        }
        $object = static::getInstance();
        $path = base_path('resources/views/'.str_replace('.', '/', $name));
        $path = $path.".latte";
        return $object->exists($path);
    }
    
    
    
}
