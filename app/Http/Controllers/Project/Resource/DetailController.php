<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Project\Resource;

use App\Http\Controllers\Project\Controller;
use App\Model\User;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {

    public function getIndex($projectId, $resourceId) {
        $project = \App\Model\Project::find($projectId);
        $this->getView()->addParameter('project', $project);
        
        $this->getView()->addParameter('project', $project->getResources()->where('resource.id', $resourceId)->first());
        
        $this->prepareMenu($project);
        
    }

}
