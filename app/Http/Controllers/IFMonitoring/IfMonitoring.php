<?php

namespace App\Http\Controllers\IFMonitoring;

use App\Helpers\Monitoring\ImportFlow\StepPoolMonitoring;
use Illuminate\Routing\Controller;

class IfMonitoring extends Controller
{

    public function index() {


        $ifMonitoring = new StepPoolMonitoring();
        $data = $ifMonitoring->getGraphData();

        $params = [];
        $params['graph'] = $data;

        return view("default.IFMonitoring.index", $params);
    }
}
