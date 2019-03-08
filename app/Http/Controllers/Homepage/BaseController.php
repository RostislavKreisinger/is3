<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexController;
use Monkey\Config\Application\ProjectEndpointBaseUrl;

/**
 * Class BaseController
 * @package App\Http\Controllers\Homepage
 */
class BaseController extends Controller {
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
        $this->getView()->addParameter('baseApiUrl', ProjectEndpointBaseUrl::getInstance()->getImportSupportApiUrl());
    }
}