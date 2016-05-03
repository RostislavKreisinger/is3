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
use Monkey\DateTime\DateTimeHelper;
/**
 * Description of B1_ResetAutomatTestButtonController
 *
 * @author Tomas
 */
class ShiftNextCheckDateButtonController extends Controller {
    
    
    protected function buttonAction() {
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');
        
        $nextCheckDate = Input::get('next_check_date', null);
        if($nextCheckDate === 'now'){
            $nextCheckDate = DateTimeHelper::getInstance('NOW')->changeMinutes(2);
        } else {
            $nextCheckDate = new DateTimeHelper();
            $nextCheckDate->changeDays(7);
        }
        
        try{
            $result = DB::connection('mysql-master-app')
                            ->table('resource_setting_v2')
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
    }

}
