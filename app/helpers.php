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

}

namespace Monkey {

    function action($name, $parameters = [], $absolute = true) {
        if (!strpos($name, '@')) {
            $name .= '@getIndex';
        }
        return \action($name, $parameters, $absolute);
    }

}



