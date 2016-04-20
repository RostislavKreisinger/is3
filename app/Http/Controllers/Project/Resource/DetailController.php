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
use App\Model\Project;
use App\Model\Resource;
use Monkey\Breadcrump\BreadcrumbItem;
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
        $this->resource = $resource = $project->getResources()->where('resource.id', $resourceId)->first();
        
        $viewName = 'default.project.resource.detail.'.$resource->codename;
        if( ViewFinder::existView($viewName) ){
            $this->getView()->setBody($viewName);
        }
        
        $this->getView()->addParameter('project', $project);
        $this->getView()->addParameter('resource', $resource);
        
        $this->prepareMenu($project);
        
        
    }
    
    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(ProjectDetailController::class, ['project_id' =>$this->project->id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('resource', 'Resource', \Monkey\action(self::class, ['project_id' =>$this->project->id, 'resource_id' => $this->resource->id ])));
    }

}
