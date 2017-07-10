<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Controllers\Button\Resource;

use App\Http\Controllers\Button\Controller;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;
/**
 * Description of B1_ResetAutomatTestButtonController
 *
 * @author Tomas
 */
class B1_ResetAutomatTestButtonController extends Controller {
    
    
    protected function buttonAction() {
        $user = \Auth::user();
        if(!$user->can('project.resource.button.reset.automattest')){
            return;
        }
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');
        try{
            $result = DB::connection('mysql-master-app')
                            ->table('resource_setting')
                            ->where('project_id', '=', $projectId)
                            ->where('resource_id', '=', $resourceId)
                            ->update(array(
                                'active' => 0,
                                'ttl' => 5
                            ));
            $result = DB::connection('mysql-master-app')
                            ->table('resource_setting')
                            ->where('project_id', '=', $projectId)
                            ->where('resource_id', '=', $resourceId)
                            ->update(array(
                                'active' => 0,
                                'ttl' => 5
                            ));
        }catch(Exception $e){
            $this->getMessages()->addError('Something wrong');
        }
        $this->getMessages()->addMessage('Automattest was reset');
        
    }

}
