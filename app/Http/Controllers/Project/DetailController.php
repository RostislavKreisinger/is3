<?php

namespace App\Http\Controllers\Project;


use App\Exceptions\ProjectUserMissingException;
use App\Helpers\API\ISAPIRequest;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Services\ProjectsService;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\ImportSupport\Project;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {
    /**
     * @var Project $project
     */
    private $project;

    /**
     * @param int $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function show(int $projectId) {
        $projectsService = new ProjectsService($this->getAPIClient());
        $this->project = $projectsService->find($projectId);

        try {
            return view('default.project.detail', [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'menu' => $this->prepareMenu($this->project),
                'project' => $this->project
            ]);
        } catch (ProjectUserMissingException $exception) {
            return redirect('error')->with('errorMessage', $exception->getMessage());
        }
    }

    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', url("/project/{$this->project->id}")));
    }
}