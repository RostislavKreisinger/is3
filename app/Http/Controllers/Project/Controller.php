<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Project\Resource\DetailController;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use Monkey\ImportSupport\Project;
use Monkey\Menu\Menu;
use Monkey\View\View;
use Tr;

class Controller extends BaseController {

    
   
    /**
     * 
     * @param Project $project
     * @return type
     */
    protected function prepareMenu($project = null) {
        View::share('project', $project);
        $menu = $this->getMenu();
        if($project !== null){
            $resources = new Menu($project->name, '#');
            $resources->setOpened(true);
            $projectResources = $project->getResources(); // ->get();
            foreach ($projectResources as $resource){
                $menuItem = new Menu(
                                Tr::_($resource->btf_name),
                                action(DetailController::routeMethod('getIndex'), ['project_id'=>$project->id, 'resource_id' => $resource->id]) 
                                );
                if(!$resource->isValid()){
                    $menuItem->addClass('invalid');
                }
                $resources->addMenuItem($menuItem);
            }
            if(count($projectResources) == 0){
                $resources->addMenuItem(new Menu("-- EMPTY --", ""));
            }
            $menu->addMenuItem($resources);
            
            $userProjects = new Menu('Projects', '#');
            foreach ($project->getUser()->getProjects()->get() as $userProject){
                if($project->id == $userProject->id) continue;
                $menuItem = new Menu(
                            $userProject->name, 
                            action(ProjectDetailController::routeMethod('getIndex'), ['project_id'=>$userProject->id])
                        );
                if(!$userProject->isValid()){
                    $menuItem->addClass('invalid');
                }
                $userProjects->addMenuItem($menuItem);
            }
            if(count($userProjects->getList()) == 0){
                $userProjects->addMenuItem(new Menu("-- EMPTY --", null));
            }
            $menu->addMenuItem($userProjects);
        }
        return $menu;
    }

    
}
