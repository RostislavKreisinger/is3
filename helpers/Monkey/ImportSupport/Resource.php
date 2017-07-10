<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport;

use App\Model\Resource as ResourceModel;
use App\Model\Stack;
use App\Model\StackExtend;
use Auth;
use DB;
use Exception;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\ImportSupport\Resource\Button\B00_ShowButton;
use Monkey\ImportSupport\Resource\Button\B0_TestButton;
use Monkey\ImportSupport\Resource\Button\B1_ResetAutomatTestButton;
use Monkey\ImportSupport\Resource\Button\B2_RepairAutomatTestButton;
use Monkey\ImportSupport\Resource\Button\B3_RepairDailyButton;
use Monkey\ImportSupport\Resource\Button\B4_RepairHistoryButton;
use Monkey\ImportSupport\Resource\Button\B5_ReactivateHistoryButton;
use Monkey\ImportSupport\Resource\Button\B5_ResetHistoryButton;
use Monkey\ImportSupport\Resource\Button\B6_ResetDailyButton;
use Monkey\ImportSupport\Resource\Button\BaseButton;
use Monkey\ImportSupport\Resource\Button\ButtonList;
use Monkey\ImportSupport\Resource\Button\Other\ClearStackButton;
use Monkey\ImportSupport\Resource\Button\Other\ShiftNextCheckDateButton;
use Monkey\ImportSupport\Resource\Button\Other\UnconnectButton;
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
    const STATUS_DEACTIVE = 'deactive';
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
    
    /*
    public function __construct(ResourceModel $resource, $project_id) {
        $this->fill($resource->getAttributes());
        $this->setProject_id($project_id);
        $this->resourceStats = new ResourceStats();
        $this->addDefaultButtons();
    }*/
    
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
        $builder =  MDDatabaseConnections::getMasterAppConnection()
                    ->table('resource_setting as rs')
                    ->join($this->tbl_setting.' as crs', 'rs.id', '=', 'crs.resource_setting_id')
                    ->where('rs.resource_id', '=', $this->id)
                    ->where('rs.project_id', '=', $this->getProject_id())
                    ->where('rs.active', '!=', 3)
                    ->select('*')
                    ;
        //  vdQuery($builder);
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
        $B0_TestButton = new B0_TestButton($this->project_id, $this->id);
        $B1_ResetAutomatTestButton = new B1_ResetAutomatTestButton($this->project_id, $this->id);
        $B2_RepairAutomatTestButton = new B2_RepairAutomatTestButton($this->project_id, $this->id);
        $B3_RepairDailyButton = new B3_RepairDailyButton($this->project_id, $this->id);
        $B4_RepairHistoryButton = new B4_RepairHistoryButton($this->project_id, $this->id);
        $B5_ResetHistoryButton = new B5_ResetHistoryButton($this->project_id, $this->id);
        $B5_ReactivateHistoryButton = new B5_ReactivateHistoryButton($this->project_id, $this->id);
        $B6_ResetDailyButton = new B6_ResetDailyButton($this->project_id, $this->id);
        
        $ShiftNextCheckDateButton = new ShiftNextCheckDateButton($this->project_id, $this->id);
        $UnconnectButton = new UnconnectButton($this->project_id, $this->id);
        $ClearStackButton = new ClearStackButton($this->project_id, $this->id);
        
        
        if($this->getStateHistory() === Resource::STATUS_MISSING_RECORD){
            $B5_ResetHistoryButton->setError('Chybi zaznam v history poolu, resenim je spustit automattest');
            $B5_ReactivateHistoryButton->setError('Chybi zaznam v history poolu, resenim je spustit automattest');
            $B4_RepairHistoryButton->setError('Chybi zaznam v history poolu, resenim je spustit automattest');
        }
        
        if($this->getStateDaily() === Resource::STATUS_MISSING_RECORD){
            $B6_ResetDailyButton->setError('Chybi zaznam v daily poolu, resenim je spustit automattest');
            $B3_RepairDailyButton->setError('Chybi zaznam v daily poolu, resenim je spustit automattest');
        }
        
        if($this->isValid()){
            $UnconnectButton->setError('Nelze odpojit resource pokud neni chybny');
        }
        
        $user = Auth::user();
        if($user->can('project.resource.button.test.test_download')){
            $this->addButton($B0_TestButton);
        }
        if($user->can('project.resource.button.reset.automattest')){
            $this->addButton($B1_ResetAutomatTestButton);
        }
        if($user->can('project.resource.button.reset.daily')){
            $this->addButton($B6_ResetDailyButton);
        }
        if($user->can('project.resource.button.reactivate.history')){
            $this->addButton($B5_ReactivateHistoryButton);
        }
        if($user->can('project.resource.button.reset.history')){
            $this->addButton($B5_ResetHistoryButton);
        }
        
        
        if($user->can('project.resource.button.repair.automattest')){
            $this->addButton($B2_RepairAutomatTestButton);
        }
        if($user->can('project.resource.button.repair.daily')){
            $this->addButton($B3_RepairDailyButton);
        }
        if($user->can('project.resource.button.repair.history')){
            $this->addButton($B4_RepairHistoryButton);
        }
        
        if($user->can('project.resource.button.delete.shift_date')){
            $this->addButton($ShiftNextCheckDateButton);
        }
        
        if($user->can('project.resource.button.delete.clear_stack')){
            $this->addButton($ClearStackButton);
        }
        
        if($user->can('project.resource.button.delete.unconnect')){
            $this->addButton($UnconnectButton);
        }
        
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
    
    public function getModel() {
        return $this->resource;
    }
    
    protected function isStateValid($state) {
        return !in_array($state, array(self::STATUS_ERROR, self::STATUS_MISSING_RECORD));
    }
    
    public function getStack() {
        $builder = $this->hasMany(Stack::class, 'resource_id')
                        ->where('project_id', '=', $this->getProject_id())
                ;
        return $builder->get();
    }
    
    public function getStackExtend() {
        $builder = $this->hasMany(StackExtend::class, 'resource_id')
                        ->where('project_id', '=', $this->getProject_id())
                ;
        return $builder->get();
    }
    
    /**
     * 
     * @return array return associative array where key is name of value
     */
    public function getConnectionDetail() {
        return [];
    }

    
  

}
