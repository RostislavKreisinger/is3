<?php

namespace App\Http\Controllers\Search;


use App\Http\Controllers\Project\DetailController;
use App\Model\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Monkey\Helpers\Strings;
use Monkey\View\View;
use Redirect;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class ProjectController extends BaseController {
    public function getIndex() {
        $search = $originSearch = Input::get('search', null);

        if (!is_numeric($search)) {
            $search = Strings::alpha2id($search);
        }
        /*
        $alpha2id = alpha2id($search);
        if(intValue($alpha2id) && $alpha2id > 0){
            $search = $alpha2id;
        }
        */
        if (is_numeric($search)) {
            $project = Project::find($search);

            if ($project) {
                return Redirect::action(DetailController::routeMethod('getIndex'), ['project_id' => $project->id]);
            }
        }

        $this->getView()->addParameter('search', $search);
        View::share('projectSearch', $originSearch);
        
        $projects = Project::where(function(Builder $where) use ($originSearch) {
                    $where->orWhere('name', 'like', "%$originSearch%");
                })
                ->get();
                
        $this->getView()->addParameter('projects', $projects);
    }
}