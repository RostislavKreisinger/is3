<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Project\DetailController as ProjectDetailController;
use Illuminate\Support\Collection;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDImportFlowConnections;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Integers;
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
            $menuItem = new Menu("{$project->getName()} [{$project->getInvalidResourceCount()}]", action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->getId()]));
            $menuItem->setTitle($project->getId());
            $invalidProjects->addMenuItem($menuItem);
            if (++$k == 10) {
                break;
            }
        }
        $menu->addMenuItem($invalidProjects);

        $newProjects = new Menu('New projects', '#');
        foreach ($this->getNewProjects() as $project) {
            $menuItem = new Menu($project->name, action(ProjectDetailController::routeMethod('getIndex'), ['project_id' => $project->id]));
            $menuItem->setTitle($project->id);

            $invalidProject = $invalidProjectList->getProject($project->id);
            if ($invalidProject) {
                $menuItem->setName("{$invalidProject->getName()} [{$invalidProject->getInvalidResourceCount()}]");
                $menuItem->setTitle($project->id);
                $menuItem->addClass('invalid');
            }

            $newProjects->addMenuItem($menuItem);
        }
        $menu->addMenuItem($newProjects);
        return $menu;
    }

    protected function getImportFlowStatusForProject($projectId, $resource) {
        $flowStatus = $this->getFlowStatus($projectId, $resource->id);

        $resource->getStateDailyImportFlow();
        $resource->getStateHistoryImportFlow();

        $uniques = array();


        if ($flowStatus) {
            foreach ($flowStatus as $key => $status) {
                switch ($status->is_history) {
                    case 0:
                        $status->is_history_status = "daily";
                        break;
                    case 1:
                        $status->is_history_status = "history";
                        break;
                    case 2:
                        $status->is_history_status = "tester";
                        break;
                    default:
                        $status->is_history_status = NULL;
                }

                $step = 1;
                switch ($status->code){
                    case 'import':
                        $step = 1;
                        break;
                    case 'etl':
                        $step = 2;
                        break;
                    case 'calc':
                        $step = 3;
                        break;
                    case 'output':
                        $step = 4;
                        break;
                }
                $status->flow_step = $step;
                $status->is_in_gearman_queue = 0;


                $state = $status->final_state;

                if ($this->getNdDigitFromNumber(1, $state) !== 0) {
                    $type = "Import";
                } else if ($this->getNdDigitFromNumber(2, $state) !== 0) {
                    $type = "Etl";
                } else if ($this->getNdDigitFromNumber(3, $state) !== 0) {
                    $type = "Calc";
                } else if ($this->getNdDigitFromNumber(4, $state) !== 0) {
                    $type = "Output";
                }

                $status->refresh_link = $this->getFlowStatusLink($status->unique, $type);

                $uniques[$status->unique] = $this->getFlowStatusKey($status);
                $flowStatus[$this->getFlowStatusKey($status)] = $status;
                unset($flowStatus[$key]);
            }
        }

        uasort($flowStatus, function ($a, $b) {
            return Integers::compare($a->flow_step, $b->flow_step);
        });

        $dbUniques = MDImportFlowConnections::getGearmanConnection()->table('gearman_queue_finished')->get(['unique_key']);
        foreach ($dbUniques as $dbUnique){
            if(array_key_exists(substr($dbUnique->unique_key, 0, 43), $flowStatus)){
                $flowStatus[$dbUnique->unique_key]->is_in_gearman_queue = 2;
            }
        }

        $dbUniques = MDImportFlowConnections::getGearmanConnection()->table('gearman_queue')->get(['unique_key']);
        foreach ($dbUniques as $dbUnique){
            if(array_key_exists(substr($dbUnique->unique_key, 0, 43), $flowStatus)){
                $flowStatus[$dbUnique->unique_key]->is_in_gearman_queue = 1;
            }
        }

        return array_values($flowStatus);
    }

    private function getFlowStatusKey($status) {

        return "{$status->unique}--{$status->flow_step}";
    }

    private function getNdDigitFromNumber($position, $number) {
        return (int)$number[--$position];
    }

    private function getFlowStatus($projectId, $resourceId) {
        return MDImportFlowConnections::getImportFlowConnection()->select('CALL flowStatus(?,?)', array($projectId, $resourceId));
    }

    private function getFlowStatusLink($uniqueId, $type) {
        return "https://import-flow.monkeydata.com/management/{$type}/?unique={$uniqueId}";
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

    protected function getImportFlowStatuses() {
        $resultCollection = new Collection();

        $ifImportCollection = $this->getImportFlowStatusesCollection('if_import');
        $ifEtlCollection = $this->getImportFlowStatusesCollection('if_etl');
        $ifCalcCollection = $this->getImportFlowStatusesCollection('if_calc');
        $ifOutputCollection = $this->getImportFlowStatusesCollection('if_output');

        $this->mergeCollectionsByKey($resultCollection, $ifImportCollection, "unique");
        $this->mergeCollectionsByKey($resultCollection, $ifEtlCollection, "unique");
        $this->mergeCollectionsByKey($resultCollection, $ifCalcCollection, "unique");
        $this->mergeCollectionsByKey($resultCollection, $ifOutputCollection, "unique");

        $projectIds = $resultCollection->pluck('project_id');
        $projectsCollection = $this->getProjectsCollection($projectIds);

        $this->mergeCollectionsByKey($resultCollection, $projectsCollection, "project_id");

        $resultCollection->map(function ($member) {
            if ($member->start_at !== null) {
                $date = DateTimeHelper::getCloneSelf($member->start_at);
                $formatted = $date->format('m-d H:i:s');

                $member->start_date = $formatted;
            } else {
                $member->start_date = "-";
            }
            if (!isset($member->name)) {
                $member->name = "name";
            }
        });

        return $resultCollection;
    }

    /**
     * Returns ImportFlow statuses
     * @param $table
     * @return Collection
     */
    private function getImportFlowStatusesCollection($table) {
        $activeAlias = substr($table, strpos($table, "_") + 1);

        $subQuery = MDDatabaseConnections::getImportFlowConnection()->table("{$table} as t2")->selectRaw('MAX(id) as id')->groupBy('project_id')->toSql();

        $allProjects = new Collection(MDDatabaseConnections::getImportFlowConnection()->table("{$table} as t1")->select(["t1.project_id", "t1.unique", "t1.active as " . $activeAlias, "t1.start_at"])->join(\DB::raw('(' . $subQuery . ') as t2'), "t1.id", "=", "t2.id")->get());

        return $allProjects->whereIn($activeAlias, [2, 3]);
    }

    /**
     * Merge new collection data with the base collection
     * @param Collection $baseCollection
     * @param Collection $newCollection
     * @param $key
     */
    private function mergeCollectionsByKey(Collection &$baseCollection, Collection $newCollection, $key) {

        $newCollection->map(function ($item) use (&$baseCollection, $key) {

            $targets = $baseCollection->where($key, $item->{$key});

            $targets->map(function ($target, $baseKey) use (&$baseCollection, $key, $item) {
                if ($target->{$key} == $item->{$key}) {
                    foreach ($item as $itemKey => $itemValue) {
                        if (!isset($target->$itemKey)) {
                            $target->$itemKey = $item->{$itemKey};
                        }
                    }
                }
            });

            if ($targets->count() == 0) {
                $baseCollection->push($item);
            }
        });
    }

    /**
     * Returns project name
     * @param $projectIds
     * @return Collection
     */
    private function getProjectsCollection($projectIds) {

        return new Collection(MDDatabaseConnections::getMasterAppConnection()->table('project')->select(['id as project_id', 'name'])->whereIn('id', $projectIds->all())->get());
    }

}
