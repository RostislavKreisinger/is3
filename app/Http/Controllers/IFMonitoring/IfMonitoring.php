<?php

namespace App\Http\Controllers\IFMonitoring;

use App\Helpers\Monitoring\ImportFlow\StepPoolMonitoring;
use Illuminate\Routing\Controller;

class IfMonitoring extends Controller
{

    public function index() {


        $ifMonitoring = new StepPoolMonitoring();
        $data = $ifMonitoring->getGraphData();

        $out = [];
        foreach ($data as $rowData){
            if($rowData->isAverage()){
                $out[] = $rowData;
                //vd($rowData);
                $rowData->getImportTimeToStartValue();
            }
        }

        $params = [];
        $params['graph'] = $data;
        $params['colorSet'][0] = ['#00DBFF','#006DFF','#33E900','#33A800','#FF9900','#FF4200','#DBDBDB','#494949'];
        $params['colorSet'][1] = ['#00DBFF','#006DFF','#33E900','#33A800','#FF9900','#FF4200','#DBDBDB','#494949'];
        //$params['colorSet'][1] = ['#00B6FF','#0049FF','#33BD00','#337C00','#FF5700','#FF1600','#929292','#242424'];
        return view("default.IFMonitoring.circle", $params);
        //return view("default.IFMonitoring.index", $params);
    }
}
