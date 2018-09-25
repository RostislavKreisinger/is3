<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\IndexController;

/**
 * Class ImportFlowControlPoolController
 * @package App\Http\Controllers\Homepage
 */
class ImportFlowControlPoolController extends BaseController {
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
    }
}