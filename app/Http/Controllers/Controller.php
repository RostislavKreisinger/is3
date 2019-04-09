<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Homepage\BrokenFlowController;
use App\Http\Controllers\Homepage\ImportFlowController;
use App\Http\Controllers\Homepage\ImportFlowControlPoolController;
use App\Http\Controllers\Homepage\ImportFlowStatsController;
use App\Http\Controllers\Homepage\Importv2Controller;
use App\Http\Controllers\Homepage\ResourcesController;
use App\Http\Controllers\Homepage\TestedNotRunningProjectsController;
use App\Http\Controllers\OrderAlert\IndexController;
use App\Http\Controllers\Homepage\LargeFlowController;
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
use URL;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseViewController {
    /**
     * @var Project
     */
    private $newProjects;

    /**
     * @var PoolList
     */
    private $poolList;

    public function __construct() {
        parent::__construct();
        View::share('poolList', $this->getPoolList());
    }

    protected function prepareMenu() {
        $menu = $this->getMenu();

        $menu->addMenuItem(new Menu("Import-flow", URL::action(ImportFlowController::getMethodAction())));
        $menu->addMenuItem(new Menu("Import-flow stats", URL::action(ImportFlowStatsController::getMethodAction())));
        $menu->addMenuItem(new Menu("Order Alert", URL::action(IndexController::getMethodAction())));
        $menu->addMenuItem(new Menu("IF Control Pool", URL::action(ImportFlowControlPoolController::getMethodAction())));
        $menu->addMenuItem(new Menu("Resources", URL::action(ResourcesController::getMethodAction())));
        $menu->addMenuItem(new Menu("Broken flows", URL::action(BrokenFlowController::getMethodAction())));
        $menu->addMenuItem(new Menu("Tested not running", URL::action(TestedNotRunningProjectsController::getMethodAction())));

        return $menu;
    }

    protected function getImportFlowStatusForProject($projectId, $resource) {
        $flowStatus = $this->getFlowStatus($projectId, $resource->id);

        $resource->getStateDailyImportFlow();
        $resource->getStateHistoryImportFlow();

        $uniques = array();


        if ($flowStatus) {
            foreach ($flowStatus as $key => $status) {
                if (property_exists($status, 'deleted_at') && !empty($status->deleted_at)) {
                    unset($flowStatus[$key]);
                    continue;
                }

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
                switch ($status->code) {
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

        $dbUniques = MDImportFlowConnections::getGearmanConnection()->table('gearman_queue')->get(['unique_key']);
        foreach ($dbUniques as $dbUnique) {
            $uniqueKey = substr($dbUnique->unique_key, 0, 43);

            if (array_key_exists($uniqueKey, $flowStatus)) {
                $flowStatus[$uniqueKey]->is_in_gearman_queue = 1;
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
        $sql = <<<'SQL'
        SELECT
            `status`.`unique`,
            `status`.`is_history`,
            `status`.`workload_difficulty`,
            istart AS import_start, 
            estart AS etl_start, 
            astart AS calc_start, 
            ostart AS output_start,
            ifinish AS import_finish, 
            efinish AS etl_finish, 
            afinish AS calc_finish, 
            ofinish AS output_finish,
            iupdated_at AS import_updated_at, 
            eupdated_at AS etl_updated_at, 
            aupdated_at AS calc_updated_at, 
            oupdated_at AS output_updated_at,
            ideleted_at AS import_deleted_at,
            edeleted_at AS etl_deleted_at,
            adeleted_at AS calc_deleted_at,
            odeleted_at AS output_deleted_at,
    
            CASE
                WHEN `status`.`final_state` LIKE '0000' THEN 'OK' 
                WHEN `status`.`final_state` LIKE '000%' THEN 'Output error' 
                WHEN `status`.`final_state` LIKE '00%' THEN 'Calc error'
                WHEN `status`.`final_state` LIKE '0%' THEN 'Etl error'
                WHEN `status`.`final_state` NOT LIKE '0%' THEN 'Import error'
                ELSE 'something else: '
            END AS `result`,
    
            CASE
                WHEN `status`.`final_state` LIKE '0000' THEN 'done'
                WHEN `status`.`final_state` LIKE '000%' THEN 'output'
                WHEN `status`.`final_state` LIKE '00%' THEN 'calc'
                WHEN `status`.`final_state` LIKE '0%' THEN 'etl'
                ELSE 'import'
            END AS `code`,
    
            `status`.`status_code` as `status_code`,
    
            CASE
                WHEN `status`.`status_code` = "import" THEN istart
                WHEN `status`.`status_code` = "etl" THEN estart
                WHEN `status`.`status_code` = "calc" THEN astart
                WHEN `status`.`status_code` = "output" THEN ostart
            END AS `start_at`,
            
            CASE
                WHEN `status`.`status_code` = "import" THEN ifinish
                WHEN `status`.`status_code` = "etl" THEN efinish
                WHEN `status`.`status_code` = "calc" THEN afinish
                WHEN `status`.`status_code` = "output" THEN ofinish
            END AS `finish_at`,
               
            CASE
                WHEN `status`.`status_code` = "import" THEN ihostname
                WHEN `status`.`status_code` = "etl" THEN ehostname
                WHEN `status`.`status_code` = "calc" THEN ahostname
                WHEN `status`.`status_code` = "output" THEN ohostname
            END AS `hostname`,
            
            CASE
                WHEN `status`.`status_code` = "import" THEN iupdated_at
                WHEN `status`.`status_code` = "etl" THEN eupdated_at
                WHEN `status`.`status_code` = "calc" THEN aupdated_at
                WHEN `status`.`status_code` = "output" THEN oupdated_at
            END AS `updated_at`,
    
            `status`.`final_state` 
        FROM (
            SELECT 
                c.date_from, 
                c.date_to, 
                c.`unique`,
                c.`is_history`,
                c.`workload_difficulty`,   
                i.active AS iactive,
                i.start_at AS istart,
                i.finish_at AS ifinish, 
                i.hostname AS ihostname,
                i.updated_at AS iupdated_at, 
                i.deleted_at AS ideleted_at, 
                e.active AS eactive,
                e.start_at AS estart,
                e.finish_at AS efinish, 
                e.hostname AS ehostname,
                e.updated_at AS eupdated_at, 
                e.deleted_at AS edeleted_at,
                a.active AS aactive,
                a.start_at AS astart,
                a.finish_at AS afinish, 
                a.updated_at AS aupdated_at, 
                a.deleted_at AS adeleted_at,
                a.hostname AS ahostname,
                o.active AS oactive,
                o.start_at AS ostart,
                o.finish_at AS ofinish, 
                o.hostname AS ohostname,
                o.updated_at AS oupdated_at, 
                o.deleted_at AS odeleted_at,
	
                CASE
                    WHEN COALESCE(i.active, 4) THEN 'import'
                    WHEN COALESCE(e.active, 4) then 'etl'
                    WHEN COALESCE(a.active, 4) then 'calc'
                    WHEN COALESCE(o.active, 4) then 'output'
                    ELSE 'done'
                END AS `status_code`,
	
                CONCAT(
                    COALESCE(i.active, 4), 
                    COALESCE(e.active, 4), 
                    COALESCE(a.active, 4), 
                    COALESCE(o.active, 4)
                ) AS final_state
            FROM if_control AS c
            LEFT JOIN if_import AS i ON c.`unique` = i.`unique`
            LEFT JOIN if_etl AS e ON c.`unique` = e.`unique`
            LEFT JOIN if_calc AS a ON c.`unique` = a.`unique`
            LEFT JOIN if_output AS o ON c.`unique` = o.`unique`
            WHERE c.project_id = ?
            AND c.resource_id = ?
            AND c.deleted_at IS NULL
        ) as `status`
        WHERE `status`.iactive != 0
        OR `status`.eactive != 0
        OR `status`.aactive != 0
        OR `status`.oactive != 0;
SQL;
        return MDImportFlowConnections::getImportFlowConnection()->select($sql, array($projectId, $resourceId));
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

        $subQuery = MDDatabaseConnections::getImportFlowConnection()->table("{$table} as t2")->selectRaw('MAX(id) as id')->groupBy('project_id')->whereNull('t2.deleted_at')->toSql();

        $allProjects = new Collection(MDDatabaseConnections::getImportFlowConnection()->table("{$table} as t1")->select(["t1.project_id", "t1.resource_id", "t1.unique", "t1.active as " . $activeAlias, "t1.start_at"])->join(\DB::raw('(' . $subQuery . ') as t2'), "t1.id", "=", "t2.id")->whereNull('t1.deleted_at')->get());

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
