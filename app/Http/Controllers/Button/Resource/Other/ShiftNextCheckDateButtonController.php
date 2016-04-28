<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Controllers\Button\Resource\Other;

use App\Http\Controllers\Button\Controller;
use DateTime;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;
use Monkey\ImportSupport\Resource;
/**
 * Description of B1_ResetAutomatTestButtonController
 *
 * @author Tomas
 */
class ShiftNextCheckDateButtonController extends Controller {
    
    
    protected function buttonAction() {
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');
        
        $nextCheckDate = new DateTime();
        $nextCheckDate->modify('+7 day');
        
        try{
            $result = DB::connection('mysql-master-app')
                            ->table(Resource::RESOURCE_SETTING)
                            ->where('project_id', '=', $projectId)
                            ->where('resource_id', '=', $resourceId)
                            ->update(array(
                                'next_check_date' => $nextCheckDate
                            ));
            $result = DB::connection('mysql-master-app')
                            ->table('resource_setting')
                            ->where('project_id', '=', $projectId)
                            ->where('resource_id', '=', $resourceId)
                            ->update(array(
                                'next_check_date' => $nextCheckDate
                            ));
        }catch(Exception $e){
            vd($e);
        }
        
        vde($result);
    }

}
