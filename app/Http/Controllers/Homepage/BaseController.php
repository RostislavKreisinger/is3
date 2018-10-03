<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexController;

/**
 * Class BaseController
 * @package App\Http\Controllers\Homepage
 */
class BaseController extends Controller {
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
    }
}