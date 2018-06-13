<?php

namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Project\Controller;
use App\Model\Project;
use Illuminate\Http\RedirectResponse;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {
    /**
     * @param int $project_id
     * @return RedirectResponse
     */
    public function getIndex(int $project_id) {
        if (!$this->can('resource.list')) {
            return $this->redirectToRoot();
        }

        $project = Project::find($project_id);
        $resources = $project->getResources();
        $this->getView()->addParameter('resources', $resources);
        
        $this->prepareMenu($project);
    }
}