<?php

namespace App\Http\Controllers\Project;


use App\Exceptions\ProjectUserMissingException;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Model\Project;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\Breadcrump\Breadcrumbs;

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
     * @return RedirectResponse
     * @throws Exception
     */
    public function getIndex($projectId) {
        $this->project = $project = Project::with(['resourceSettings' => function (HasMany $hasMany) {
            $hasMany->with(['resource']);
        }])->find($projectId);

        $this->getView()->addParameter('project', $project);
        $this->getView()->addParameter('currencyCode', $project->currency->code);

        try {
            $this->prepareMenu($project);
        } catch (ProjectUserMissingException $exception) {
            return redirect('error')->with('errorMessage', $exception->getMessage());
        }
    }

    /**
     * @param array $parameters
     * @return Breadcrumbs|void
     */
    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(self::class, ['project_id' =>$this->project->id ])));
    }
}
