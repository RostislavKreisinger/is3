<?php

namespace App\Http\Controllers\IFMonitoring;

use App\Http\Controllers\BaseViewController;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use Monkey\Connections\MDImportFlowConnections;


class IfMonitoring extends Controller
{

    public function index() {

        $con = MDImportFlowConnections::getImportFlowConnection();
        vde($con->table("if_import")->where("active","=",1)->get());

        $params = [];

        $user = new \stdClass();
        $user->name = "jajajja";

        $params['users'] = [$user];
        return view("default.IFMonitoring.index", $params);
    }


}
