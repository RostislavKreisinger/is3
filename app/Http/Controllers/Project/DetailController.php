<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Project;

use App\Exceptions\ProjectUserMissingException;
use App\Http\Controllers\User\DetailController as UserDetailController;
use App\Model\Currency;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\ImportSupport\Project;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {
    
    private $project;

    public function getIndex($projectId) {
        $this->project = $project = Project::find($projectId);
        $this->getView()->addParameter('project', $project);
        $currency = Currency::find($project->currency_id);
        if($currency){
            $currency = $currency->code;
        }
        $this->getView()->addParameter('currencyCode', $currency);
        $this->getView()->addParameter('autoreports', $project->getAutoReports());

        try {
            $this->prepareMenu($project);
        } catch (ProjectUserMissingException $exception) {
            return redirect('error')->with('errorMessage', $exception->getMessage());
        }
    }

    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = parent::breadcrumbAfterAction();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('user', 'User', \Monkey\action(UserDetailController::class, ['user_id' => $this->project->user_id ])));
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('project', 'Project', \Monkey\action(self::class, ['project_id' =>$this->project->id ])));
    }

}
