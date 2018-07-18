<?php


namespace App\Http\Controllers\Project\Resource;


use App\Http\Controllers\Project\Controller;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Model\Currency;
use App\Model\EshopType;
use App\Model\ImportSupport\ResourceError;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Project;
use Monkey\View\ViewFinder;
use stdClass;

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
     * @param $projectId
     * @param $resourceId
     * @throws Exception
     */
    public function getIndex($projectId, $resourceId) {
        $this->project = $project = Project::find($projectId);
        $this->resource = $resource = $project->getResource($resourceId);
        $resourceErrors = $resource->getResourceErrors($project->id);

        $viewName = 'default.project.resource.detail.' . $resource->codename;
        if (ViewFinder::existView($viewName)) {
            $this->getView()->setBody($viewName);
        }
        $resource->getStateTester();

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


        $stack = null;
        $stackExtend = null;
        if (!$resource->isValid()) {
            $stack = $resource->getStack();
            $stackExtend = $resource->getStackExtend();
        }

        $connectionDetail = array();
        if ($this->getUser()->can('project.resource.connection_detail')) {
            $connectionDetail = $resource->getConnectionDetail();
        }

        $this->getView()->addParameter('stack', $stack);
        $this->getView()->addParameter('stackExtend', $stackExtend);
        $this->getView()->addParameter('project', $project);
        $this->getView()->addParameter('resource', $resource);

        $this->getView()->addParameter('resourceCurrency', $resourceCurrency);
        $this->getView()->addParameter('connectionDetail', $connectionDetail);
        $this->getView()->addParameter('resourceErrors', $resourceErrors);

        $sqls = $this->getSqlFromModel($project);
        $sqls .= $this->getSqlFromModel($project->getUser());
        $sqls .= $this->getSqlFromModel($project->getUser()->getClient());
        $sqls .= $this->getSqlFromModel($project->getResourceSettings($resourceId)->first());

        if ($resourceDetail !== null) {
            $sqls .= $this->getResourceDetailSql($resourceDetail, $resource->tbl_setting);
        }

        $this->getView()->addParameter('rsexport', $sqls);
        $this->prepareMenu($project);
    }

    /**
     * @param array $parameters
     * @return void
     */
    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(ProjectDetailController::class, ['project_id' => $this->project->id])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('resource', 'Resource', \Monkey\action(self::class, ['project_id' => $this->project->id, 'resource_id' => $this->resource->id])));
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
    private function getResourceDetailSql(stdClass $object, string $table) {
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
}