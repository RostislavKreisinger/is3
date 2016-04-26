<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\InvalidProject;

use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
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
            $resourceList = \App\Model\Resource::all();
            foreach($resourceList as $resource){
                $instance->resourceList[$resource->id] = $resource;
            }
        }
        return $instance->resourceList;
    }


    
    
    
    
    
    
    
    

    /**
     * 
     * @return \Monkey\ImportSupport\InvalidProject\Project
     */
    public static function getAllInvalidProjects() {
        $instance = static::getInstance();
        if(!$instance->invalidProjects){
            $whereFunctions = array();
            $builder = $instance->getInvalidProjectsBuilder();
            $builder = $instance->addTestToResourceSetting($builder, $whereFunctions);
            $builder = $instance->addTestToImportPrepareNew($builder, $whereFunctions);
            $builder = $instance->addTestToImportPrepareStart($builder, $whereFunctions);
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
        $builder = DB::connection('mysql-select')->table('monkeydata.project as p')
                ->select(['p.id as project_id', 'p.name as project_name'])
                ->whereNull('p.deleted_at')
                ->join('user as u', 'p.user_id', '=', 'u.id')
                ->whereNull('u.deleted_at')
                ->where('u.test_user', '=', 0)
                ->join('client as c', 'u.id', '=', 'c.user_id')
                ->whereRaw('DATE_ADD(`c`.`tariff_expired`, INTERVAL ' . static::DAYS_AFTER_TARIFF_EXPIRATE . ' DAY) > NOW()')
        ;
        return $builder;
    }

    protected function addTestToImportPrepareNew(Builder &$builder, &$whereFunctions) {
        $builder->leftJoin('monkeydata_pools.import_prepare_new as ipn', function(JoinClause $join) {
                    $join->on('ipn.project_id', '=', 'rs.project_id');
                    $join->on('ipn.resource_id', '=', 'rs.resource_id');
                    return $join;
                })
                // DB::raw('IF( ipn.active IS NULL OR ipn.active = 3 OR ((ipn.active = 0 AND ipn.ttl <= 0) ),1,0) as import_prepare_new')
                ->addSelect([DB::raw('IF( ipn.active IS NULL OR ipn.active = 3 OR ((ipn.ttl <= 0) ),1,0) as import_prepare_new')])

        ;
        $whereFunctions[] = function(Builder $where) {
            $where->orWhereNull('ipn.active');
            $where->orWhere('ipn.active', '=', 3);
            $where->orWhere(function(Builder $where) {
                // $where->where('ipn.active', '=', 0);
                $where->where('ipn.ttl', '<=', 0);
                return $where;
            });
            return $where;
        };
        return $builder;
    }

    protected function addTestToImportPrepareStart(Builder &$builder, &$whereFunctions) {
        $builder->leftJoin('monkeydata_pools.import_prepare_start as ips', function(JoinClause $join) {
                    $join->on('ips.project_id', '=', 'rs.project_id');
                    $join->on('ips.resource_id', '=', 'rs.resource_id');
                    return $join;
                })
                // DB::raw('IF( ips.active = 3 OR ((ips.active = 0 AND ips.ttl <= 0) ) ,1,0) as import_prepare_start')
                ->addSelect([DB::raw('IF( ips.active = 3 OR ((ips.ttl <= 0) ) ,1,0) as import_prepare_start')])

        ;
        $whereFunctions[] = function(Builder $where) {
            // $where->orWhereNull('ips.active');
            $where->orWhere('ips.active', '=', 3);
            $where->orWhere(function(Builder $where) {
                // $where->where('ips.active', '=', 0);
                $where->where('ips.ttl', '<=', 0);
                return $where;
            });
            return $where;
        };
        return $builder;
    }

    protected function addTestToResourceSetting(Builder &$builder, &$whereFunctions) {
        $builder->join('monkeydata.' . Resource::RESOURCE_SETTING . ' as rs', 'p.id', '=', 'rs.project_id')
                ->addSelect(['rs.resource_id'])
                ->whereRaw('COALESCE(`rs`.`next_check_date`, 0) < NOW()')
                ->whereNotIn('rs.resource_id', [1, 19])
                ->addSelect([DB::raw('IF( ((rs.active = 0 AND rs.ttl <= 0) OR rs.active = 3) ,1,0) as resource_setting')])

        ;
        $whereFunctions[] = function(Builder $where) {
            $where->orWhere('rs.active', '=', 3);
            $where->orWhere(function(Builder $where) {
                $where->where('rs.active', '=', 0);
                $where->where('rs.ttl', '<=', 0);
                return $where;
            });
            return $where;
        };
        return $builder;
    }

}
