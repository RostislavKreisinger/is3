<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Model\Project;
use Monkey\View\View;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {
    
    public function getIndex() {
        View::share('invalidProjects', $this->getInvalidProjects());
        
    }
    
    protected function getInvalidProjects() {
        $projects = Project::where($column, $operator, $value)->limit(10);
        
        return $projects;
    }
}
