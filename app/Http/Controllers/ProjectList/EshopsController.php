<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\ProjectList;

use App\Http\Controllers\Controller;
use App\Model\EshopType;
use App\Model\Project;
use App\Model\Resource;
use Monkey\View\ViewRender;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class EshopsController extends Controller {

    public function getIndex($eshop_type_id = null) {
        $availableResources = EshopType::orderBy('name')->get();
        if($eshop_type_id == null){
            foreach ($availableResources as $resource){
                $eshop_type_id = $resource->id;
                break;
            }
        }
        // vde($availableResources);

        $projects = Project::join("resource_setting as rs", "project.id", '=', 'rs.project_id')
            ->where('rs.active', '=', 1)
            ->whereNull('project.deleted_at')
            ->where('rs.resource_id', '=', 4)
            ->join('resource_eshop as re', 're.resource_setting_id', '=', 'rs.id')
            ->where('re.eshop_type_id', '=', $eshop_type_id)
            ->orderBy('project.id', 'DESC')
            ->select('project.*');


        ViewRender::addParameter("availableResources", $availableResources);
        ViewRender::addParameter("currentResourceId", $eshop_type_id);

        ViewRender::addParameter("projectsCount", $projects->count());// vde("resources");
        ViewRender::addParameter("projects", $projects->simplePaginate(10));// vde("resources");
    }
}
