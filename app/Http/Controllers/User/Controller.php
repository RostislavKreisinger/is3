<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Project\DetailController;
use App\Model\User;
use Monkey\ImportSupport\InvalidProject\ProjectRepository;
use Monkey\Menu\Menu;

class Controller extends BaseController {

    
   
    /**
     * 
     * @param User $user
     * @return type
     */
    protected function prepareMenu($user = null) {
        $menu = $this->getMenu();
        if($user !== null){
            $userProjects = new Menu('Projects', '#');
            $userProjects->setOpened(true);
            $invalidProjectList = ProjectRepository::getAllInvalidProjectsOfUser($user->id);
            foreach ($user->getProjects() as $project){
                
                $menuItem = new Menu(
                                    $project->name,
                                    action(DetailController::routeMethod('getIndex'), ['project_id'=>$project->id])
                                );
                
                $invalidProject = $invalidProjectList->getProject($project->id);
                if($invalidProject){
                    $menuItem->setName("{$invalidProject->getName()} [{$invalidProject->getInvalidResourceCount()}]");
                    $menuItem->addClass('invalid');
                }
                $userProjects->addMenuItem($menuItem);
            }
            $menu->addMenuItem($userProjects);
        }
        return $menu;
    }
    
}
