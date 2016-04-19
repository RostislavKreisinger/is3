<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Project;

use App\Model\Project;
use Monkey\Breadcrump\BreadcrumbItem;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {

    public function getIndex() {
        if(!$this->can('project.list')){
            return $this->redirectToRoot();
        }
        
        $projects = Project::whereNull('deleted_at')->limit(40)->orderBy('created_at', 'desc')->get();
        $this->getView()->addParameter('projects', $projects);
        
        
    }
    
   
    
}
