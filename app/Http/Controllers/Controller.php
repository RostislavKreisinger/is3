<?php

namespace App\Http\Controllers;

use App\Model\Project;
use Monkey\Menu\Menu;
use App\Http\Controllers\Project\DetailController as ProjectDetailController;

class Controller extends BaseViewController {

    private $invalidProjects;
    private $newProjects;

    protected function prepareMenu() {
        $menu = $this->getMenu();

        $invalidProjects = new Menu('Invalid projects (' . count($this->getInvalidProjects()) . ')', '#');
        $invalidProjects->setOpened(true);
        $k = 0;
        foreach ($this->getInvalidProjects() as $project) {
            $invalidProjects->addMenuItem(new Menu($project->name, action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->id])));
            if (++$k == 10) {
                break;
            }
        }
        $menu->addMenuItem($invalidProjects);

        $newProjects = new Menu('New projects', '#');
        foreach ($this->getNewProjects() as $project) {
            $newProjects->addMenuItem(new Menu($project->name, action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->id])));
            // $newProjects->addMenuItem(new Menu("Project {$k}", action(DetailController::routeMethod('getIndex'), ['project_id'=>$k])));
        }
        $menu->addMenuItem($newProjects);

        return $menu;
    }

    protected function getInvalidProjects() {
        if ($this->invalidProjects) {
            return $this->invalidProjects;
        }
        return $this->invalidProjects = Project::limit(50)->get();
    }

    protected function getNewProjects() {
        if ($this->newProjects) {
            return $this->newProjects;
        }
        return $this->newProjects = Project::limit(10)->orderBy('created_at', 'DESC')->get();
    }

    protected function getHistoryProjects() {
        $projects = Project::limit(50)->get();
        return $projects;
    }

    protected function getAutomattestProjects() {
        $projects = Project::limit(50)->get();
        return $projects;
    }

}
