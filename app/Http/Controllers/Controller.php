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
        foreach ($this->getInvalidProjects() as $project) {
            $invalidProjects->addMenuItem(new Menu("{$project->getName()} [{$project->getInvalidResourceCount()}]", action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->getId()])));
            if (++$k == 10) {
                break;
            }
        }
        $menu->addMenuItem($invalidProjects);

        $newProjects = new Menu('New projects', '#');
        foreach ($this->getNewProjects() as $project) {
            $newProjects->addMenuItem(new Menu($project->name, action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->id])));
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
    

    protected function getNewProjects() {
        if ($this->newProjects) {
            return $this->newProjects;
        }
        return $this->newProjects = Project::limit(10)->orderBy('created_at', 'DESC')->get();
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
