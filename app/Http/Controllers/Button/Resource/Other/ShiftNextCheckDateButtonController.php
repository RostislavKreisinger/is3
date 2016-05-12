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
            $nextCheckDate = DateTimeHelper::getInstance('NOW');
        } else {
            $nextCheckDate = new DateTimeHelper();
            $nextCheckDate->changeDays(7);
        }
        
        try{
            $resourceSetting = \App\Model\ResourceSetting::where('project_id', $projectId)->where('resource_id', $resourceId)->first();
            if($resourceSetting){
                if($resourceSetting->next_check_date < $nextCheckDate->mysqlFormat()){
                    $resourceSetting->next_check_date = $nextCheckDate->mysqlFormat();
                    $resourceSetting->save();
                }
            }
            
            $resourceSettingV2 = \App\Model\ResourceSettingV2::where('project_id', $projectId)->where('resource_id', $resourceId)->first();
            if($resourceSettingV2){
                if($resourceSettingV2->next_check_date < $nextCheckDate->mysqlFormat()){
                    $resourceSettingV2->next_check_date = $nextCheckDate->mysqlFormat();
                    $resourceSettingV2->save();
                }
            }
        }catch(Exception $e){
        }
    }

}
