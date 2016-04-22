<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport;

use App\Model\Resource as ResourceModel;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Monkey\ImportSupport\Resource\ResourceStats;
/**
 * Description of Resource
 *
 * @author Tomas
 */
class Resource {
    
    const STATUS_ERROR = 'error';
    const STATUS_DEACTIVE = 'deactive';
    const STATUS_ACTIVE = 'active';
    const STATUS_DONE = 'done';
    const STATUS_RUNNING = 'runnig';
    
    const RESOURCE_SETTING = 'resource_setting_v2';


    private $project_id = null;
    
    /**
     *
     * @var ResourceModel
     */
    private $resource;
    
    /**
     *
     * @var ResourceStats 
     */
    private $resourceStats;
    
    
    public function __construct($resource, $project_id) {
        $this->setResource($resource);
        $this->setProject_id($project_id);
        $this->resourceStats = new ResourceStats();
    }
    
    public static function factory($resource, $project_id) {
        if(!($resource instanceof ResourceModel)){
            $resource = ResourceModel::find($resource);
        }
        $namespace = __NAMESPACE__.'\\Resource\\';
        $className = false;
        if(class_exists($tmpClassName = $namespace.'ResourceV'.$resource->import_version)){
            $className = $tmpClassName;
        }
        if(class_exists($tmpClassName = $namespace.'Resources\\'.ucfirst($resource->codename).'Resource')){
            $className = $tmpClassName;
        }
        if($className !== false){
            return new $className($resource, $project_id);
        }
        throw new Exception("Resource class not found for {$resource->codename}");
    }
    
    public function getResource() {
        return $this->resource;
    }

    public function setResource($resource) {
        if(!($resource instanceof ResourceModel)){
            $resource = ResourceModel::find($resource);
        }
        $this->resource = $resource;
    }
    
    public function isValid() {
        return $this->isValidTester() && $this->isValidDaily() && $this->isValidHistory();
    }
    
    public function isValidTester() {
        return $this->getStateTester() !== Resource::STATUS_ERROR;
    }

    public function isValidDaily() {
        return $this->getStateDaily() !== Resource::STATUS_ERROR;
    }
    
    public function isValidHistory() {
        return $this->getStateHistory() !== Resource::STATUS_ERROR;
    }
    
    public function isValidContinuity() {
        return $this->getStateContinuity() !== Resource::STATUS_ERROR;
    }
    
    public function getStateTester() {
        return Resource::STATUS_DEACTIVE;
    }

    public function getStateDaily() {
        return Resource::STATUS_DEACTIVE;
    }
    
    public function getStateHistory() {
        return Resource::STATUS_DEACTIVE;
    }
    
    public function getStateContinuity() {
        return Resource::STATUS_DEACTIVE;
    }
    

    public function getProject_id() {
        return $this->project_id;
    }

    public function getResourceStats() {
        return $this->resourceStats;
    }

    public function setProject_id($project_id) {
        $this->project_id = $project_id;
    }

    public function setResourceStats(type $resourceStats) {
        $this->resourceStats = $resourceStats;
    }
    
    protected function loadResourceStats(){
        
    }
    
    
    


}
