<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Controllers\Button\Resource\Error;

use App\Http\Controllers\Button\Controller;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;
/**
 * Description of B1_ResetAutomatTestButtonController
 *
 * @author Tomas
 */
class SendErrorTestButtonController extends Controller {
    
    
    protected function buttonAction() {
        $user = \Auth::user();
        if(!$user->can('project.resource.error.send')){
            return;
        }
        $projectId = Input::get('project_id');
        $errorId = Input::get('error_id');
        
        $error = \App\Model\ImportSupport\ResourceError::find($errorId);
        $project = \App\Model\Project::find($projectId);
        $resource = $error->getResource();
        vde($resource);
        $dth = new \Monkey\DateTime\DateTimeHelper();
        
        
        $errorMessage = "{$project->name} ({$project->id}) :: {$resource->name} " 
                        . "<b>{$error->error}</b><br>"
                        . "<i>{$error->solution}</i>";
        $key = "project_{$projectId}_error_{$errorId}";
        try{
            $result = DB::connection('mysql-app-support')
                            ->table('crm_tickets')
                            ->where('unique_action', '=', $key)
                            ->first();
            if($result === null){
                $result = DB::connection('mysql-app-support')
                                ->table('crm_tickets')
                                ->insert(array(
                                    'client_user_id' => $project->user_id,
                                    'user_id' => 59,
                                    'create_user_id' => 1,
                                    'active' => 1,
                                    'name' => 'Import support error',
                                    'description' => $errorMessage,
                                    'next_action' => $dth->mysqlFormat(),
                                    'unique_action' => $key,
                                    'ticket_type_id' => 5 
                                ));
            }
        }catch(Exception $e){
            $this->getMessages()->addError('Something wrong');
        }
        $this->getMessages()->addMessage('Error was sent.');
        
    }

}
