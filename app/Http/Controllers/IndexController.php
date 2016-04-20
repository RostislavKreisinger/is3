<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use App\Model\Project;
use Monkey\Menu\Menu;
use Monkey\View\View;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {
    
   
    
    public function getIndex() {
        vde(\Monkey\ImportSupport\Project::getAllInvalidProjects());
        
        $p = new \Monkey\ImportSupport\Project(1087);
        
        // $r = \Monkey\ImportSupport\Resource::factory(4);
        
        foreach($p->getProjectResources() as $resource){
            echo $resource->getResource()->id; vd($resource->isValid());
        }
        
        
        vde($p->getProjectResources());
        
        
        View::share('invalidProjects', $this->getInvalidProjects());
        View::share('newProjects', $this->getNewProjects());
        View::share('historyProjects', $this->getHistoryProjects());
        View::share('automattestProjects', $this->getAutomattestProjects());
    }
    
    
    
    
            
}
