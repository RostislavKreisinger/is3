<?php

namespace {
    if (!defined('LARAVEL_FRAMEWORK_5')) {
        define('LARAVEL_FRAMEWORK_5', true);
    }

    function url_md($name, $parameters = [], $absolute = true) {
        if (!strpos($name, '@')) {
            $name .= '@getIndex';
        }
        return action($name, $parameters, $absolute);
    }
    
    function queryLog(){
        global $queryLog;
        $time = 0;
        foreach($queryLog as $query){
            $time += $query->time;
        }
        vd($time);
        vde($queryLog); 
    }
    
    function clearQueryLog(){
        global $queryLog;
        $queryLog = array(); 
    }

}

namespace Monkey {

    function action($name, $parameters = [], $absolute = true) {
        if (!strpos($name, '@')) {
            $name .= '@getIndex';
        }
        return \action($name, $parameters, $absolute);
    }

}



