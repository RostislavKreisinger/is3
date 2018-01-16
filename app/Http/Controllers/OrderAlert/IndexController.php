<?php

namespace App\Http\Controllers\OrderAlert;


use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\View\View;

class IndexController extends BaseController {

    public function getIndex() {
        $eshops = MDOrderAlertConnections::getOrderAlertConnection()->table("eshop")->get();
        View::share("eshops", $eshops);
        \URL::action(DetailController::routeMethod("getIndex"), ['storeId'=>123]);
    }
}