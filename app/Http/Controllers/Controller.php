<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use App\Model\Project;
use Monkey\ImportSupport\InvalidProject\Project as Project2;
use Monkey\ImportSupport\InvalidProject\ProjectRepository;
use Monkey\Menu\Menu;

class Controller extends BaseViewController {

    private $invalidProjects;
    private $newProjects;

    protected function prepareMenu() {
        $menu = $this->getMenu();
        
        $invalidProjects = new Menu('Invalid projects (' . count($this->getInvalidProjects()) . ')', '#');
        $invalidProjects->setOpened(true);
        $k = 0;
        $invalidProjectList = $this->getInvalidProjects();
        foreach ($invalidProjectList as $project) {
            $menuItem = new Menu(
                                "{$project->getName()} [{$project->getInvalidResourceCount()}]",
                                action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->getId()])
                            );
            $menuItem->setTitle($project->getId());
            $invalidProjects->addMenuItem($menuItem);
            if (++$k == 10) {
                break;
            }
        }
        $menu->addMenuItem($invalidProjects);
        
        $newProjects = new Menu('New projects', '#');
        foreach ($this->getNewProjects() as $project) {
            $menuItem = new Menu(
                        $project->name,
                        action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->id])
                    );
            $menuItem->setTitle($project->id);
            
            $invalidProject = $invalidProjectList->getProject($project->id);
            if($invalidProject){
                $menuItem->setName("{$invalidProject->getName()} [{$invalidProject->getInvalidResourceCount()}]");
                $menuItem->addClass('invalid');
            }
            
            $newProjects->addMenuItem($menuItem);
        }
        $menu->addMenuItem($newProjects);
        return $menu;
    }

    /**
     * 
     * @return Project2[]
     */
    protected function getInvalidProjects() {
        return ProjectRepository::getAllInvalidProjects();
    }
    

    /**
     * 
     * @return \Monkey\ImportSupport\Project
     */
    protected function getNewProjects() {
        if ($this->newProjects) {
            return $this->newProjects;
        }
        return $this->newProjects = \Monkey\ImportSupport\Project::limit(10)->orderBy('created_at', 'DESC')->get();
    }
    
    protected function getDailyProjects() {
        return ProjectRepository::getDailyInvalidProjects();
    }

    protected function getHistoryProjects() {
        return ProjectRepository::getHistoryInvalidProjects();
//        $projects = Project::limit(50)->get();
//        return $projects;
    }

    protected function getAutomattestProjects() {
        return ProjectRepository::getAutomattestInvalidProjects();
//        $projects = Project::limit(50)->get();
//        return $projects;
    }

}
