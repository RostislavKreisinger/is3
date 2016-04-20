<?php

namespace {

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



