<?php

namespace App\Http\Controllers\Project\Resource;


use App\Exceptions\ProjectUserMissingException;
use App\Http\Controllers\Project\Controller;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Model\EshopType;
use App\Model\Project;
use App\Model\ResourceSetting;
use App\Model\Resource;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\Config\Application\ProjectEndpointBaseUrl;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Resource\Button\B00_ShowButton;
use Monkey\ImportSupport\Resource\Button\ButtonList;
use Monkey\View\ViewFinder;
use stdClass;

/**
 * Class DetailController
 * @package App\Http\Controllers\Project\Resource
 * @author Tomas
 */
class DetailController extends Controller {
    /**
     * @param $projectId
     * @param $resourceId
     * @return RedirectResponse
     * @throws Exception
     */
    public function getIndex($projectId, $resourceId) {
        $project = Project::with(['resourceSettings' => function (HasMany $query) use ($resourceId) {
            $query->where('resource_id', $resourceId);
        }])->find($projectId);

        /**
         * @var ResourceSetting $resourceSetting
         * @var Resource $resource
         */
        $resourceSetting = $project->resourceSettings->where('resource_id', $resourceId)->first();
        $resource = $resourceSetting->resource;

        if (request()->getMethod() === 'PUT') {
            if ($this->findAction($resourceSetting)) {
                return back()->with(['message' => 'Success!']);
            }
        }

        $viewName = "default.project.resource.detail.{$resource->codename}";

        if (ViewFinder::existView($viewName)) {
            $this->getView()->setBody($viewName);
        }

        $resourceDetail = $resourceSetting->connectionData()->first();

        if ($resourceDetail !== null) {
            if ($resource->id == 4) {
                $this->getView()->addParameter('eshopType', EshopType::find($resourceDetail->eshop_type_id));
            }

            $this->getView()->addParameter('resourceDetail', get_object_vars($resourceDetail));
        }

        $this->getView()->addParameter('project', $project);
        $this->getView()->addParameter('resource', $resource);
        $this->getView()->addParameter('resourceSetting', $resourceSetting);
        $this->getView()->addParameter('buttons', $this->getButtons($projectId, $resourceId));
        $this->getView()->addParameter('ifBaseUrl', ProjectEndpointBaseUrl::getInstance()->getImportFlowUrl());

        $this->getView()->addParameter('resourceCurrency', $resourceSetting->currency);
        $this->getView()->addParameter('historyInterval', $resourceSetting->custom_import_history_interval / (24 * 60 * 60));

        $sqls = $this->getSqlFromModel($project);
        $sqls .= $this->getSqlFromModel($project->user);
        $sqls .= $this->getSqlFromModel($project->user->client);
        $sqls .= $this->getSqlFromModel($resourceSetting);

        if ($resourceDetail !== null) {
            $sqls .= $this->getResourceDetailSql($resourceDetail, $resource->tbl_setting);
        } else {
            $sqls .= "Record in {$resource->tbl_setting} is missing!";
        }

        $this->getView()->addParameter('rsexport', $sqls);

        try {
            $this->prepareMenu($project);
        } catch (ProjectUserMissingException $exception) {
            return redirect('error')->with('errorMessage', $exception->getMessage());
        }
    }

    /**
     * @param int $projectId
     * @param int $resourceId
     * @return ButtonList
     */
    private function getButtons(int $projectId, int $resourceId): ButtonList {
        $buttons = new ButtonList();

        if (Auth::user()->can('project.resource.button.test.show_data')) {
            $buttons->addButton(new B00_ShowButton($projectId, $resourceId));
        }

        return $buttons;
    }

    /**
     * @param array $parameters
     * @return void
     */
    protected function breadcrumbAfterAction($parameters = array()) {
        $userId = Project::whereId($parameters['project_id'])->first(['user_id'])->user_id;
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $userId])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(ProjectDetailController::class, ['project_id' => $parameters['project_id']])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('resource', 'Resource', \Monkey\action(self::class, ['project_id' => $parameters['project_id'], 'resource_id' => $parameters['resource_id']])));
    }

    /**
     * @param Model $model
     * @return string
     */
    private function getSqlFromModel(Model $model): string {
        $sql = "INSERT INTO `" . $model->getTable() . "` SET ";
        $values = [];

        foreach ($model->getAttributes() as $name => $value) {
            if (is_null($value)) {
                $values[] = "`{$name}` = NULL";
            } else {
                $values[] = "`{$name}` = '{$value}'";
            }
        }

        $sql .= implode(', ', $values) . ';' . PHP_EOL;
        return $sql;
    }

    /**
     * @param stdClass $object
     * @param string $table
     * @return string
     */
    private function getResourceDetailSql(stdClass $object, string $table): string {
        $resource_detail_columns = MDDatabaseConnections::getMasterAppConnection()->getSchemaBuilder()->getColumnListing($table);
        $sql = "INSERT INTO `" . $table . "` SET ";
        $values = [];

        foreach (get_object_vars($object) as $name => $value) {
            if (!in_array($name, $resource_detail_columns)) {
                continue;
            }

            if (is_null($value)) {
                $values[] = "`{$name}` = NULL";
            } else {
                $values[] = "`{$name}` = '{$value}'";
            }
        }

        $sql .= implode(', ', $values) . ';' . PHP_EOL;
        return $sql;
    }

    /**
     * @param ResourceSetting $resourceSetting
     */
    private function activate(ResourceSetting $resourceSetting) {
        $resourceSetting->activate()->save();
    }

    /**
     * @param ResourceSetting $resourceSetting
     */
    private function deactivate(ResourceSetting $resourceSetting) {
        if (!Auth::user()->can('project.resource.button.delete.unconnect')) {
            return;
        }

        $resourceSetting->deactivate()->save();
    }

    /**
     * @param ResourceSetting $resourceSetting
     */
    private function test(ResourceSetting $resourceSetting) {
        $resourceSetting->test()->save();
    }

    /**
     * @param ResourceSetting $resourceSetting
     * @return bool
     */
    private function findAction(ResourceSetting $resourceSetting): bool {
        $found = false;

        switch (request()->input('action')) {
            case 'activate':
                $this->activate($resourceSetting);
                $found = true;
                break;
            case 'deactivate':
                $this->deactivate($resourceSetting);
                $found = true;
                break;
            case 'test':
                $this->test($resourceSetting);
                $found = true;
                break;
        }

        return $found;
    }
}
