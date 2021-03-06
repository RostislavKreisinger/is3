<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\ProjectList;

use App\Http\Controllers\Controller;
use App\Model\Project;
use App\Model\Resource;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\View\ViewRender;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class ResourcesController extends Controller {
    
    public function getIndex($resource_id = null) {
        $availableResources = Resource::orderBy('name')->get();
        if($resource_id == null){
            foreach ($availableResources as $resource){
                $resource_id = $resource->id;
                break;
            }
        }
        // vde($availableResources);

        $projects = Project::join("resource_setting as rs", "project.id", '=', 'rs.project_id')
            ->where('rs.active', '=', 1)
            ->where('rs.resource_id', '=', $resource_id)
            ->whereNull('project.deleted_at')
            ->join('client', 'project.user_id', '=', 'client.user_id')
            ->orderBy(MDDatabaseConnections::getMasterAppConnection()->raw('client.tariff_expired > NOW()'), 'DESC')
            ->orderBy('project.id', 'DESC')
            ->select(['project.*', MDDatabaseConnections::getMasterAppConnection()->raw('client.tariff_expired > NOW() as tariff')]);


        ViewRender::addParameter("availableResources", $availableResources);
        ViewRender::addParameter("currentResourceId", $resource_id);

        ViewRender::addParameter("projectsCount", $projects->count());// vde("resources");
        ViewRender::addParameter("projects", $projects->simplePaginate(10));// vde("resources");
    }
}
