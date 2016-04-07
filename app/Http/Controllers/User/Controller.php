<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Project\DetailController;
use App\Model\User;
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
            foreach ($user->getProjects()->get() as $project){
                $userProjects->addMenuItem(new Menu($project->name, action(DetailController::routeMethod('getIndex'), ['project_id'=>$project->id])));
            }
            $menu->addMenuItem($userProjects);
        }
        return $menu;
    }
    
}
