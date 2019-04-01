<?php


namespace App\Http\Controllers\Project\Resource;


use App\Exceptions\ProjectUserMissingException;
use App\Helpers\API\ISAPIClient;
use App\Helpers\API\ISAPIRequest;
use App\Http\Controllers\Project\Controller;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Model\Currency;
use App\Model\EshopType;
use App\Model\ImportSupport\ResourceError;
use App\Model\ResourceSetting;
use App\Services\ProjectsService;
use App\Services\ResourceSettingsService;
use Eloquent;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Project;
use Monkey\View\ViewFinder;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DetailController
 * @package App\Http\Controllers\Project\Resource
 * @author Tomas
 */
class DetailController extends Controller {
    /**
     * @var Project
     */
    private $project;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var ResourceSetting $resourceSetting
     */
    private $resourceSetting;

    /**
     * @param int $resourceSettingId
     * @return ResponseFactory|Factory|View|Response
     * @throws Exception
     */
    public function show(int $resourceSettingId) {
        $rsService = new ResourceSettingsService($this->getAPIClient());
        $this->resourceSetting = $rsService->find($resourceSettingId);

        if ($this->resourceSetting === null) {
            return response('', 404);
        }

        $projectsService = new ProjectsService($this->getAPIClient());
        $this->project = $projectsService->find($this->resourceSetting->project_id);
        $resourceDetail = $rsService->getDetail($resourceSettingId);

        try {
            return view(
                'default.project.resource.detail.resource',
                [
                    'breadcrumbs' => $this->getBreadcrumbs(),
                    'eshopType' => $this->getCatalogsService()->findEshopType($resourceDetail->eshop_type_id),
                    'menu' => $this->prepareMenu($this->project),
                    'resourceSetting' => $this->resourceSetting,
                    'resourceDetail' => $resourceDetail
                ]
            );
        } catch (ProjectUserMissingException $exception) {
            return redirect('error')->with('errorMessage', $exception->getMessage());
        }
    }

    /**
     * @param $projectId
     * @param $resourceId
     * @return RedirectResponse
     * @throws Exception
     */
    public function getIndex($projectId, $resourceId) {
        $client = new ISAPIClient;
        $resourceSettings = $client->call(new ISAPIRequest('base/resource-settings', [], ['project_id' => $projectId, 'resource_id' => $resourceId]));
        vde($resourceSettings);
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);

        if (request()->getMethod() === 'PUT') {
            if ($this->findAction()) {
                return back()->with(['message' => 'Success!']);
            }
        }

        $resourceErrors = $resource->getResourceErrors($project->id);

        $viewName = 'default.project.resource.detail.' . $resource->codename;
        if (ViewFinder::existView($viewName)) {
            $this->getView()->setBody($viewName);
        }

        $currencyId = 4;
        if(is_null($resource->getResourceStats()) && $resource->getResourceStats()->getResourceSetting() ){
            $currencyId = $resource->getResourceStats()->getResourceSetting()->currency_id;
        }
        $resourceCurrency = Currency::find($currencyId);
        $resourceDetail = $resource->getResourceDetail();

        if ($resourceDetail === null) {
//            throw new Exception("Missing resource detail for project {$project->id} and resource {$resource->id}");
        } else {
            if ($resource->id == 4) {
                $this->getView()->addParameter('eshopType', EshopType::find($resourceDetail->eshop_type_id));
            }

            $this->getView()->addParameter('resourceDetail', $resourceDetail);
        }
        // $this->getView()->addParameter('importFlowStatus', $this->getImportFlowStatusForProject($projectId, $resource));


        $connectionDetail = array();

        if ($this->getUser()->can('project.resource.connection_detail')) {
            $connectionDetail = $resource->getConnectionDetail();
        }

        $resourceSettings = $project->resourceSettings($resourceId)->first();

        $this->getView()->addParameter('project', $project);
        $this->getView()->addParameter('resource', $resource);

        $this->getView()->addParameter('resourceCurrency', $resourceCurrency);
        $this->getView()->addParameter('connectionDetail', $connectionDetail);
        $this->getView()->addParameter('resourceErrors', $resourceErrors);
        $this->getView()->addParameter('historyInterval', $resourceSettings->custom_import_history_interval / (24 * 60 * 60));

        $sqls = $this->getSqlFromModel($project);
        $sqls .= $this->getSqlFromModel($project->getUser());
        $sqls .= $this->getSqlFromModel($project->getUser()->getClient());
        $sqls .= $this->getSqlFromModel($resourceSettings);

        if ($resourceDetail !== null) {
            $sqls .= $this->getResourceDetailSql($resourceDetail, $resource->tbl_setting);
        }

        $this->getView()->addParameter('rsexport', $sqls);

        try {
            $this->prepareMenu($project);
        } catch (ProjectUserMissingException $exception) {
            return redirect('error')->with('errorMessage', $exception->getMessage());
        }
    }

    /**
     * @param array $parameters
     * @return void
     */
    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', url("/user/{$this->project->user_id}")));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', url("/project/{$this->project->id}")));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('resource', 'Resource', url("/resource-settings/{$this->resourceSetting->id}")));
    }

    /**
     * @return Collection|static[]
     */
    protected function getErrors() {
        return ResourceError::all();
    }

    /**
     * @param Eloquent $model
     * @return string
     */
    private function getSqlFromModel(Eloquent $model): string {
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

    private function activate() {
        /**
         * @var ResourceSetting $resourceSetting
         */
        $resourceSetting = $this->project->resourceSettings($this->resource->id)->first();
        $resourceSetting->activate()->save();
    }

    private function deactivate() {
        if (!Auth::user()->can('project.resource.button.delete.unconnect')) {
            return;
        }

        /**
         * @var ResourceSetting $resourceSetting
         */
        $resourceSetting = $this->project->resourceSettings($this->resource->id)->first();
        $resourceSetting->deactivate()->save();
    }

    private function test() {
        /**
         * @var ResourceSetting $resourceSetting
         */
        $resourceSetting = $this->project->resourceSettings($this->resource->id)->first();
        $resourceSetting->test()->save();
    }

    /**
     * @return bool
     */
    private function findAction(): bool {
        $found = false;

        switch (request()->input('action')) {
            case 'activate':
                $this->activate();
                $found = true;
                break;
            case 'deactivate':
                $this->deactivate();
                $found = true;
                break;
            case 'test':
                $this->test();
                $found = true;
                break;
        }

        return $found;
    }
}