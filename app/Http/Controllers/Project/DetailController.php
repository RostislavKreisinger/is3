<?php

namespace App\Http\Controllers\Project;


use App\Exceptions\ProjectUserMissingException;
use App\Helpers\API\ISAPIClient;
use App\Helpers\API\ISAPIRequest;
use App\Http\Controllers\User\DetailController as UserDetailController;
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
        $client = new ISAPIClient;
        $result = $client->call(new ISAPIRequest("base/projects/{$projectId}", [], [], [], []));
        $this->project = new Project;
        $this->project->id = $result['data']['id'];
        $this->project->fill($result['data']['attributes']);

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