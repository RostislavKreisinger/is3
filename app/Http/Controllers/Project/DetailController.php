<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Model\User;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {

    public function getIndex($projectId) {
        $project = \App\Model\Project::find($projectId);
        $this->getView()->addParameter('project', $project);
    }

}
