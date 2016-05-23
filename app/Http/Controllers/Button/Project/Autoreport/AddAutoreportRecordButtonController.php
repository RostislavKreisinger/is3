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
class AddAutoreportRecordButtonController extends Controller {

    protected function buttonAction() {
        $user = Auth::user();
        if (!$user->can('project.autoreport.button.add')) {
            return;
        }
        $projectId = Input::get('project_id');

        try {
            $project = \App\Model\Project::find($projectId);

            $autoreport = new AutoReportPool();
            $autoreport->user_id = $project->user_id;
            $autoreport->project_id = $project->id;
            $autoreport->active = 0;
            $autoreport->date = \Monkey\DateTime\DateTimeHelper::getCloneSelf()->mysqlFormat();
            $autoreport->save();
        } catch (Exception $e) {
            $this->getMessages()->addError('Something wrong');
        }
        $this->getMessages()->addMessage('Autoreport was added');
    }

}
