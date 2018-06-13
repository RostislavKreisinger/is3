<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\IndexController;


/**
 * Class ImportFlowController
 * @package App\Http\Controllers\Homepage
 * @author Tomas
 */
class ImportFlowController extends BaseController {
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
    }
}