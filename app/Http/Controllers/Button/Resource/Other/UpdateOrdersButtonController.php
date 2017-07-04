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
class UpdateOrdersButtonController extends Controller {
    
   
    protected function buttonAction() {
        $projectId = Input::get('project_id');
        $resourceId = Input::get('resource_id');
    }

}
