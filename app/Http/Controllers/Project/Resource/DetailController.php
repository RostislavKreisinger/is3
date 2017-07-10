<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Project\Resource;

use App\Http\Controllers\Project\Controller;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Model\Currency;
use App\Model\EshopType;
use Exception;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Project;
use Monkey\View\ViewFinder;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {

    /**
     *
     * @var Project
     */
    private $project;

    /**
     *
     * @var Resource
     */
    private $resource;

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
            throw new Exception("Missing resource detail for project {$project->id} and resource {$resource->id}");
        }
        if ($resource->id == 4) {
            $this->getView()->addParameter('eshopType', EshopType::find($resourceDetail->eshop_type_id));

            //vde($resource->toArray());
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
        $this->getView()->addParameter('resourceDetail', $resourceDetail);
        $this->getView()->addParameter('resourceCurrency', $resourceCurrency);
        $this->getView()->addParameter('connectionDetail', $connectionDetail);
        $this->getView()->addParameter('resourceErrors', $resourceErrors);

        $this->prepareMenu($project);

    }

    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(ProjectDetailController::class, ['project_id' => $this->project->id])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('resource', 'Resource', \Monkey\action(self::class, ['project_id' => $this->project->id, 'resource_id' => $this->resource->id])));
    }

    protected function getErrors() {
        return \App\Model\ImportSupport\ResourceError::all();
    }
    
}
