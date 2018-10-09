<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\IndexController;

/**
 * Class LargeFlowController
 * @package App\Http\Controllers\Homepage
 */
class LargeFlowController extends BaseController {
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
    }
}