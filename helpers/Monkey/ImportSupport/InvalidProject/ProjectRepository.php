<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\InvalidProject;

use App\Model\Resource as Resource2;
use DB;
use Illuminate\Database\Query\Builder;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Pool\Pool;
use Monkey\ImportSupport\Resource;

/**
 * Description of ProjectRepository
 *
 * @author Tomas
 */
class ProjectRepository {

    const DAYS_AFTER_TARIFF_EXPIRATE = 14;
    
    /**
     *
     * @var ProjectRepository 
     */
    private static $instance = null;
    
    private $resourceList;
    
    /**
     *
     * @var ProjectList 
     */
    private $invalidProjects;
    /**
     *
     * @var ProjectList 
     */
    private $userInvalidProjects;

    /**
     * @var array
     */
    private $activeProjectIds;
    
    
    public function __construct() {
        
    }
    /**
     * 
     * @return ProjectRepository
     */
    public static function getInstance() {
        if(static::$instance === null){
            static::$instance = new ProjectRepository();
        }
        return static::$instance;
    }

    /**
     * @return array
     */
    public function getActiveProjectIds() {
        if($this->activeProjectIds === null){
            $builder = MDDatabaseConnections::getMasterAppConnection()
                ->table('project as p')
                ->join('user as u', 'p.user_id', '=', 'u.id')
                ->whereNull('p.deleted_at')
                ->whereNull('u.deleted_at')
                ->where('u.test_user', '=', 0)
                ->select(['p.id as id']);
            $this->activeProjectIds = array();
            foreach ($builder->get() as $row) {
                $this->activeProjectIds[] = $row->id;
            }
        }
        return $this->activeProjectIds;
    }

    public static function getDailyInvalidProjects() {
        $invalidProjects = static::getAllInvalidProjects();
        $dailyInvalidProjects = new ProjectList();
        foreach ($invalidProjects as $project) {
            foreach($project->getResources() as $resource){
                if($resource->getImportPrepareNew() == 1){
                    $tmp = $dailyInvalidProjects->addProject(new Project($project->getId(), $project->getName()));
                    $tmp->addResource($resource);
                }
            }
        }
        return $dailyInvalidProjects;
    }
    
    
    public static function getHistoryInvalidProjects() {
        $invalidProjects = static::getAllInvalidProjects();
        $historyInvalidProjects = new ProjectList();
        foreach ($invalidProjects as $project) {
            foreach($project->getResources() as $resource){
                if($resource->getImportPrepareStart() == 1){
                    $tmp = $historyInvalidProjects->addProject(new Project($project->getId(), $project->getName()));
                    $tmp->addResource($resource);
                }
            }
        }
        return $historyInvalidProjects;
    }
    
    public static function getAutomattestInvalidProjects() {
        $invalidProjects = static::getAllInvalidProjects();
        $automattestInvalidProjects = new ProjectList();
        foreach ($invalidProjects as $project) {
            foreach($project->getResources() as $resource){
                if($resource->getResourceSetting() == 1){
                    $tmp = $automattestInvalidProjects->addProject(new Project($project->getId(), $project->getName()));
                    $tmp->addResource($resource);
                }
            }
        }
        return $automattestInvalidProjects;
    }

    public static function getResourceList() {
        $instance = static::getInstance();
        if($instance->resourceList === null){
            $instance->resourceList = array();
            $resourceList = Resource2::all();
            foreach($resourceList as $resource){
                $instance->resourceList[$resource->id] = $resource;
            }
        }
        return $instance->resourceList;
    }


    
    
    
    
    
    
    public static function getAllInvalidProjectsOfUser($userId) {
        $instance = static::getInstance();
        if(!$instance->userInvalidProjects){
            $whereFunctions = array();
            $builder = $instance->getInvalidProjectsBuilder();
            $builder->where('p.user_id', '=', $userId);
            $builder = $instance->addTestToResourceSetting($builder, $whereFunctions);
            $builder->where(function(Builder $where) use ($whereFunctions) {
                foreach ($whereFunctions as $whereFnc) {
                    $where->orWhere($whereFnc);
                }
            });
            $builder->orderBy('rs.next_check_date', 'ASC')->orderBy('p.created_at', 'desc');
             // vdQuery($builder);
            $data = $builder->get();
            $projectList = new ProjectList($data);
            $instance->userInvalidProjects = $projectList;
        }
        return $instance->userInvalidProjects;
    }
    

    
    
    /**
     * @return Project
     */
    public static function getAllInvalidProjects() {
        $instance = static::getInstance();
        if(!$instance->invalidProjects){
            $whereFunctions = array();
            $builder = $instance->getInvalidProjectsBuilder();
            $builder = $instance->addTestToResourceSetting($builder, $whereFunctions);
            $builder->where(function(Builder $where) use ($whereFunctions) {
                foreach ($whereFunctions as $whereFnc) {
                    $where->orWhere($whereFnc);
                }
            });
            $builder->orderBy('rs.next_check_date', 'ASC')->orderBy('p.created_at', 'desc');
             // vdQuery($builder);
            $data = $builder->get();
            $projectList = new ProjectList($data);
            $instance->invalidProjects = $projectList;
        }
        return $instance->invalidProjects;
    }
    

    protected function getInvalidProjectsBuilder() {
        $builder = MDDatabaseConnections::getMasterAppConnection()->table('monkeydata.project as p')
                ->select(['p.id as project_id', 'p.name as project_name'])
                ->whereNull('p.deleted_at')
                ->join('user as u', 'p.user_id', '=', 'u.id')
                ->whereNull('u.deleted_at')
                ->where('u.test_user', '=', 0)
                ->join('client as c', 'u.id', '=', 'c.user_id')
                ->whereRaw('(DATE_ADD(`c`.`tariff_expired`, INTERVAL ' . static::DAYS_AFTER_TARIFF_EXPIRATE . ' DAY) > NOW() OR  `c`.`tariff_expired` IS NULL)')
        ;
        return $builder;
    }

    protected function addTestToResourceSetting(Builder &$builder, &$whereFunctions) {
        $builder->join('monkeydata.' . Resource::RESOURCE_SETTING . ' as rs', 'p.id', '=', 'rs.project_id')
                ->addSelect(['rs.resource_id'])
                ->whereRaw('COALESCE(`rs`.`next_check_date`, 0) < NOW()')
                ->whereNotIn('rs.resource_id', [1, 19])
                ->addSelect([DB::raw('IF( ((rs.active = 0 AND rs.ttl <= 0)) ,1,0) as resource_setting')])
                ->whereIn('rs.active', array(0, 1, 2))
        ;
        $whereFunctions[] = function(Builder $where) {
            // $where->orWhere('rs.active', '=', 3);
            $where->orWhere(function(Builder $where) {
                $where->where('rs.active', '=', 0);
                $where->where('rs.ttl', '<=', 0);
                return $where;
            });
            return $where;
        };
        return $builder;
    }
    
    public static function getTesterPool() {
        $data = MDDatabaseConnections::getMasterAppConnection()
            ->table('monkeydata.'.Resource::RESOURCE_SETTING.' as ips')
            ->join("monkeydata.project as p", "p.id", "=", "ips.project_id")
            ->join("monkeydata.user as u", "u.id", "=", "p.user_id")
            ->whereNull("u.deleted_at")
            ->whereNull("p.deleted_at")
            ->select(['*', DB::raw('COUNT(*) AS `out`'),DB::raw('COUNT(*) AS `all`')])
            ->where('ips.active', 0)
            ->where('ips.ttl', '>', 0)
            ->where('u.test_user', '=', 0)
            ->get();
        return new Pool($data);
    }
}
