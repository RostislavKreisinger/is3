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
class ClearStackButtonController extends Controller {
    
    
    protected function getRedirect() {
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');
        return redirect(\Monkey\action(\App\Http\Controllers\Project\Resource\DetailController::class, array('project_id' => $projectId, 'resource_id' => $resourceId)));
    }
    
    protected function buttonAction() {
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');        
        try{
            $result = DB::connection('mysql-import-pools')
                            ->table('stack')
                            ->where('project_id', '=', $projectId)
                            ->where('resource_id', '=', $resourceId)
                            ->delete();
            
            $result = DB::connection('mysql-import-pools')
                            ->table('stack_extend')
                            ->where('project_id', '=', $projectId)
                            ->where('resource_id', '=', $resourceId)
                            ->delete();
        }catch(Exception $e){
            $this->getMessages()->addError('Something wrong');
        }
        $this->getMessages()->addMessage('Stack was deleted');
    }

}
