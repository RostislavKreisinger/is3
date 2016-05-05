<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Resource;

use DB;
use Monkey\Resource\TableConfig\Column;
use Monkey\Resource\TableConfig\TableConfig;

/**
 * Description of Resource
 *
 * @author Tomas
 */
class ResourceList {
    /**
     *
     * @var Resource 
     */
    protected $resources = array();
    
    
    public function __construct($client_id) {
        
        $this->init($client_id);
    }
    
    private function init($client_id) {
        
        $this->initByDB($client_id);
        /*
        $heureka = $this->getResource(2);
        $heureka->addTable(new Table("f_heureka_stats", $client_id))->setHasDateId(true)->setHasProjectId(true);
        
        $facebook = $this->getResource(3);
        $facebook->addTable(new Table("f_facebook", $client_id))->setHasDateId(true)->setHasProjectId(true);
        
        $eshop = $this->getResource(4);
        $eshop->addTable(new Table("f_eshop_order", $client_id))->setHasDateId(true)->setHasProjectId(true);
        $eshop->addTable(new Table("f_eshop_order_detail", $client_id))->setHasProjectId(true);
        $eshop->addTable(new Table("d_eshop_customer", $client_id))->setHasProjectId(true);
        $eshop->addTable(new Table("d_eshop_product", $client_id))->setHasProjectId(true);
        $eshop->addTable(new Table("d_eshop_catalog", $client_id))->setHasProjectId(true);
        $eshop->addTable(new Table("d_eshop_combination", $client_id));
                
        $adwords = $this->getResource(5);
        $adwords->addTable(new Table("f_adwords", $client_id))->setHasDateId(true)->setHasProjectId(true);
        
        $sklik = $this->getResource(6);
        $sklik->addTable(new Table("f_sklik", $client_id))->setHasDateId(true)->setHasProjectId(true);
        
        $ga = $this->getResource(9);
        $ga->addTable(new Table("f_ga_direct", $client_id))->setHasDateId(true)->setHasProjectId(true);
        $ga->addTable(new Table("f_ga_data", $client_id));
        $ga->addTable(new Table("d_ga_metrics"));
        $ga->addTable(new Table("d_ga_combination", $client_id));
        
        $twitter = $this->getResource(18);
        $twitter->addTable(new Table("f_twitter_", $client_id))->setHasDateId(true)->setHasProjectId(true)->setResourceVersion(2);
        
        $zbozi = $this->getResource(24);
        $zbozi->addTable(new Table("data_zbozi_", $client_id))->setHasDateId(true)->setHasProjectId(true)->setResourceVersion(2);
        
        $getResponse = $this->getResource(25);
        $getResponse->addTable(new Table("d_getresponse_campaigns_", $client_id))->setHasProjectId(true)->setResourceVersion(2);
        $getResponse->addTable(new Table("f_getresponse_stats_", $client_id))->setHasDateId(true)->setHasProjectId(true)->setResourceVersion(2);
        
         */
   
//        echo "<pre>";
//        var_dump($zbozi);
//        exit;
    }
    
    private function initByDB($client_id) {
        
//        $t = new TableConfig();
//        $c = new Column();
//        $c->setName('test');
//        $t->addColumn($c);
//        
//        $c = new Column();
//        $c->setName('pepa');
//        $t->addColumn($c);
//        vd($t);
//        $ser = json_encode($t);
//        vd($ser);
//        $tmp = new TableConfig($ser);
//        vde($tmp);
        
        $resources = DB::connection('mysql-select-app')->table("resource_tables as rt")
                ->select('*')
                ->whereRaw("actual = (".DB::connection('mysql-select-app')->table('resource_tables as rt_tmp')->selectRaw('MAX(actual)')->whereRaw('rt.resource_id = rt_tmp.resource_id')->toSql().")" )
                ->orderBy('resource_id')->orderBy('order')
                ->get()
                ;
        
        foreach($resources as $resource){
//            if($resource->resource_id == 4){
//                $res = $this->getResource($resource->resource_id);
//                $table = $res->addTable(new Table($resource->table_name));
//                $table->getTableConfig()->jsonDeserialize($resource->import_support_show_config);
//            }
            $res = $this->getResource($resource->resource_id);
            $table = $res->addTable(new Table($resource->table_name));
            $table->getTableConfig()->jsonDeserialize($resource->import_support_show_config);
            if ($resource->is_catalog == 0) {
                $table->setClient_id($client_id);
            }
            
        }
    }



    public function addResource($index, Resource $resource) {
        $this->resources[$index] = $resource;
        return $this;
    }
    
    /**
     * 
     * @param type $id
     * @return Resource
     */
    public function getResource($resource_id) {
        if(isset($this->resources[$resource_id])){
            return $this->resources[$resource_id];
        }
        $res = new Resource();    
        $this->addResource($resource_id, $res);
        return $this->getResource($resource_id); 
    }
    
}
