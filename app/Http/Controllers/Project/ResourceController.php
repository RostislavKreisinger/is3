<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Project\Controller;
use App\Model\User;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class ResourceController extends Controller {

    public function getIndex($project_id) {
        if(!$this->can('resource.list')){
            return $this->redirectToRoot();
        }
        $project = \App\Model\Project::find($project_id);
        $resources = $project->getResources();
        $this->getView()->addParameter('resources', $resources);
        
        $this->prepareMenu($project);
        
        
    }

}
