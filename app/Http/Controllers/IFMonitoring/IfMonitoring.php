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
        $params['graph'] = $out;

        return view("default.IFMonitoring.circle", $params);
    }
}
