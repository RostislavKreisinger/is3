<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use Monkey\ImportSupport\InvalidProject\Project as Project2;
use Monkey\ImportSupport\InvalidProject\ProjectRepository;
use Monkey\ImportSupport\Pool\PoolList;
use Monkey\ImportSupport\Project;
use Monkey\Menu\Menu;
use Monkey\View\View;

class Controller extends BaseViewController {

    /**
     *
     * @var Project 
     */
    private $newProjects;

    /**
     *
     * @var PoolList 
     */
    private $poolList;

    public function __construct() {
        parent::__construct();
        View::share('poolList', $this->getPoolList());
    }

    protected function prepareMenu() {
        $menu = $this->getMenu();

        $invalidProjects = new Menu('Invalid projects (' . count($this->getInvalidProjects()) . ')', '#');
        $invalidProjects->setOpened(true);
        $k = 0;
        $invalidProjectList = $this->getInvalidProjects();
        foreach ($invalidProjectList as $project) {
            $menuItem = new Menu(
                    "{$project->getName()} [{$project->getInvalidResourceCount()}]", action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->getId()])
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
                    $project->name, action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->id])
            );
            $menuItem->setTitle($project->id);

            $invalidProject = $invalidProjectList->getProject($project->id);
            if ($invalidProject) {
                $menuItem->setName("{$invalidProject->getName()} [{$invalidProject->getInvalidResourceCount()}]");
                $menuItem->setTitle($project->getId());
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
     * @return Project
     */
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
    }

    protected function getAutomattestProjects() {
        return ProjectRepository::getAutomattestInvalidProjects();
    }

    protected function getPoolList() {
        if ($this->poolList === null) {
            $this->poolList = new PoolList();
            $this->initPoolList();
        }
        return $this->poolList;
    }
    
    protected function initPoolList() {
        $this->getHistoryPool();
        $this->getTesterPool();
        $this->getDailyPool();
    }

    protected function getHistoryPool() {
        $this->getPoolList()->setHistoryPool(ProjectRepository::getHistoryPool());
        return $this->getPoolList()->getHistoryPool();
    }

    protected function getDailyPool() {
        $this->getPoolList()->setDailyPool(ProjectRepository::getDailyPool());
        return $this->getPoolList()->getDailyPool();
    }

    protected function getTesterPool() {
        $this->getPoolList()->setTesterPool(ProjectRepository::getTesterPool());
        return $this->getPoolList()->getTesterPool();
    }

}
