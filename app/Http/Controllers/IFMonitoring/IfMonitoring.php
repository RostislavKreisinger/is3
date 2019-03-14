<?php

namespace App\Http\Controllers\IFMonitoring;

use App\Helpers\Monitoring\ImportFlow\StepPoolMonitoring;
use App\Http\Controllers\BaseViewController;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Monkey\Connections\MDImportFlowConnections;


class IfMonitoring extends Controller
{

    public function index() {

//        $con = MDImportFlowConnections::getImportFlowConnection();
//        vde($con->table("if_import")->where("active","=",1)->get());
//
//
//
//        $user = new \stdClass();
//        $user->name = "jajajja";

        $ifMonitoring = new StepPoolMonitoring();
        $data = $ifMonitoring->selectBaseStepData();

        $params = [];
        $params['graph'] = $data;

        return view("default.IFMonitoring.index", $params);
    }


}
