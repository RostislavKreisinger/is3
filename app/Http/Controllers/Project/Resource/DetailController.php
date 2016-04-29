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
use App\Model\Resource;
use Exception;
use Monkey\Breadcrump\BreadcrumbItem;
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
        $this->resource = $resource = $project->getResource( $resourceId );
        
        $viewName = 'default.project.resource.detail.'.$resource->codename;
        if( ViewFinder::existView($viewName) ){
            $this->getView()->setBody($viewName);
        }
        $resource->getStateTester();
        $resourceCurrency = Currency::find( $resource->getResourceStats()->getResourceSetting()->currency_id );
        $resourceDetail =  $resource->getResourceDetail();
        
        if($resourceDetail === null){
            throw new Exception("Missing resource detail for project {$project->id} and resource {$resource->id}");
        }
        if($resource->id == 4){
            $this->getView()->addParameter('eshopType', EshopType::find($resourceDetail->eshop_type_id)  );
        }
        
        
        $this->getView()->addParameter('project', $project);
        $this->getView()->addParameter('resource', $resource);
        $this->getView()->addParameter('resourceDetail', $resourceDetail);
        $this->getView()->addParameter('resourceCurrency', $resourceCurrency);
        
        $this->prepareMenu($project);
    }
    
    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(ProjectDetailController::class, ['project_id' =>$this->project->id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('resource', 'Resource', \Monkey\action(self::class, ['project_id' =>$this->project->id, 'resource_id' => $this->resource->id ])));
    }

}
