<?php

namespace Monkey\ImportSupport;


use App\Model\Resource as ResourceModel;
use Auth;
use Exception;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Resource\Button\B00_ShowButton;
use Monkey\ImportSupport\Resource\Button\BaseButton;
use Monkey\ImportSupport\Resource\Button\ButtonList;
use Monkey\ImportSupport\Resource\Interfaces\IResource;
use Monkey\ImportSupport\Resource\ResourceStats;

/**
 * Description of Resource
 *
 * @method Resource find($id, $columns = array()) 
 * @author Tomas
 */
class Resource extends ResourceModel implements IResource {
    
    const STATUS_ERROR = 'error';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';
    const STATUS_DONE = 'done';
    const STATUS_RUNNING = 'running';
    const STATUS_MISSING_RECORD = 'missing';
    
    
    const RESOURCE_SETTING = 'resource_setting';


    private $project_id = null;
    
    
    /**
     *
     * @var ResourceStats
     */
    private $resourceStats;
    
    /**
     *
     * @var ButtonList
     */
    private $buttons = array();
    
    public function __construct(array $attributes = array(), $project_id = null) {
        parent::__construct($attributes);
        $this->setProject_id($project_id);
        $this->resourceStats = new ResourceStats();
        
        $this->buttons = new ButtonList();
        $this->addDefaultButtons();
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
            return new $className($resource->getAttributes(), $project_id);
        }
        throw new Exception("Resource class not found for {$resource->codename}");
    }
    
    public function getResource() {
        return $this;
    }

    public function getResourceDetail() {
        $builder = MDDatabaseConnections::getMasterAppConnection()
                    ->table('resource_setting as rs')
                    ->join($this->tbl_setting.' as crs', 'rs.id', '=', 'crs.resource_setting_id')
                    ->where('rs.resource_id', '=', $this->id)
                    ->where('rs.project_id', '=', $this->getProject_id())
                    // ->where('rs.active', '!=', 3)
                    ->select('*');

        return $builder->first();
    }

    public function setResource($resource) {
        if(!($resource instanceof ResourceModel)){
            $resource = ResourceModel::find($resource);
        }
        $this->resource = $resource;
    }
    
    public function isValid(){
        return $this->isValidTester() && $this->isValidDaily() && $this->isValidHistory();
    }
    
    public function isValidTester() {
        return $this->isStateValid($this->getStateTester());
    }

    public function isValidDaily() {
        return $this->isStateValid($this->getStateDaily());
    }
    
    public function isValidHistory() {
        return $this->isStateValid($this->getStateHistory()); 
    }
    
    public function isValidContinuity() {
        return $this->isStateValid($this->getStateContinuity());
    }
    
    public function getStateTester() {
        return Resource::STATUS_INACTIVE;
    }

    public function getStateDaily() {
        return Resource::STATUS_INACTIVE;
    }
    
    public function getStateHistory() {
        return Resource::STATUS_INACTIVE;
    }
    
    public function getStateContinuity() {
        return Resource::STATUS_INACTIVE;
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

    public function setResourceStats($resourceStats) {
        $this->resourceStats = $resourceStats;
    }
    
    protected function loadResourceStats(){
        
    }

    protected function addShowDataButton(){
        $B00_ShowButton = new B00_ShowButton($this->project_id, $this->id);
        $user = Auth::user();
        if($user->can('project.resource.button.test.show_data')){
            $this->addButton($B00_ShowButton);
        }
    }
    
    protected function addDefaultButtons() {
        $this->addShowDataButton();
    }
    
    /**
     * 
     * @return ButtonList
     */
    public function getButtons(){
        return $this->buttons;
    }
    
    /**
     * 
     * @return BaseButton
     */
    public function getButton($code){
        return $this->getButtons()->getButton($code);
    }
    
    /**
     * 
     * @param BaseButton $button
     */
    public function addButton(BaseButton $button) {
        $this->getButtons()->addButton($button); 
    }
    
    protected function isStateValid($state) {
        return !in_array($state, array(self::STATUS_ERROR, self::STATUS_MISSING_RECORD));
    }
    
    /**
     * 
     * @return array return associative array where key is name of value
     */
    public function getConnectionDetail() {
        return [];
    }
}