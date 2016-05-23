<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Button\Project\Autoreport;

use App\Http\Controllers\Button\Controller;
use App\Model\AutoReportPool;
use Auth;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;

/**
 * Description of B1_ResetAutomatTestButtonController
 *
 * @author Tomas
 */
class ActivateAutoreportRecordButtonController extends Controller {

    protected function buttonAction() {
        $user = Auth::user();
        if (!$user->can('project.autoreport.button.activate')) {
            return;
        }
        $autoreportId = Input::get('autoreport_id');
        try {
            $autoreport = \App\Model\AutoReportPool::find($autoreportId);
            $autoreport->active = 1;
            $autoreport->errormessage = null;
            $autoreport->save();
        } catch (Exception $e) {
            $this->getMessages()->addError('Something wrong');
        }
        $this->getMessages()->addMessage('Autoreport was activated');
    }

}
