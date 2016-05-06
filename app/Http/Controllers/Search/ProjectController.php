<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Project\DetailController;
use App\Model\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Redirect;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class ProjectController extends BaseController {
    
    public function getIndex() {
        $search = Input::get('search', null);
        
        $alpha2id = alpha2id($search);
        if(intValue($alpha2id) && $alpha2id > 0){
            $search = $alpha2id;
        }
        
        if(intValue($search)){
            $project = Project::find($search);
            if($project){
                return Redirect::action(DetailController::routeMethod('getIndex'), ['project_id' => $project->id]);
            }
        }
        
        $projects = Project::where(function(Builder $where) use ($search) {
                    $where->orWhere('name', 'like', "%$search%");
                })
                ->get();
                
        $this->getView()->addParameter('projects', $projects);
        
    }
    
    
}
