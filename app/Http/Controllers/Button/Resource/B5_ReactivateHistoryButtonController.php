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
class B5_ReactivateHistoryButtonController extends Controller {

    protected function buttonAction() {
        $user = \Auth::user();
        if(!$user->can('project.resource.button.reactivate.history')){
            return;
        }
        
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');

        $date_to = new \DateTime();
        $date_to->modify('+1 day');
        $date_from = clone $date_to;
        $date_from->modify('-2 year');
        
        try {
            $result = DB::connection('mysql-import-pools')
                    ->table('import_prepare_start')
                    ->where('project_id', '=', $projectId)
                    ->where('resource_id', '=', $resourceId)
                    ->update(array(
                        'active' => 1,
                        'ttl' => 5
                    ));
        }catch(Exception $e){
            $this->getMessages()->addError('Something wrong');
        }
        $this->getMessages()->addMessage('Reactivate history was success');
    }

}
