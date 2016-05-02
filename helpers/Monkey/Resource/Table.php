<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Resource;

use DB;
use Monkey\Resource\TableConfig\TableConfig;
use Illuminate\Database\Query\Builder;

/**
 * Description of Resource
 *
 * @author Tomas
 */
class Table {

    protected $id = null;
    protected $name = null;
    protected $database = null;
    protected $hasProjectId = false;
    protected $hasDateId = false;
    protected $client_id = null;
    protected $resourceVersion = 2;
    
    private $DBcolumns = null;


    protected $tableConfig = null;

    public function __construct($name, $client_id = null) {
        $this->setName($name);
        $this->setClient_id($client_id);
        $this->tableConfig = new TableConfig();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getDbTableName() {
        return $this->replaceClientId($this->getName());
    }
    
    
    protected function replaceClientId($text) {
        return str_replace("[[client_id]]", $this->getClient_id(), $text);
    }
    
    public function getDBName() {
        $tableName = $this->name;
        if($this->getDatabase() !== null){
            $tableName = $this->getDatabase() . "." . $tableName;
        }        
        return $tableName;
    }

    public function getQueryName() {
        $tablename = $this->getDBName();
        $tablename = $this->replaceClientId($tablename);
        return $tablename;
    }
    
    public function getDbTableColumns() {
        if($this->DBcolumns === null){
            $columns = DB::connection("data_import")
                    ->table('information_schema.COLUMNS')
                    ->where('TABLE_SCHEMA', '=', $this->getDatabase())
                    ->where('TABLE_NAME', '=', $this->getDbTableName())
                    ->get(['COLUMN_NAME'])
                    ;
            $result = array();
            foreach($columns as $column){
                $result[] = $column->COLUMN_NAME; 
            }
            $this->DBcolumns = $result;
        }
        return $this->DBcolumns;
    }
    
    public function hasDbColumn($columnName) {
        return in_array($columnName, $this->getDbTableColumns());
    }

    public function getHasProjectId() {
        return $this->hasProjectId;
    }

    public function getHasDateId() {
        return $this->hasDateId;
    }

    public function getClient_id() {
        return $this->client_id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setName($name) {
        $array = explode('.', $name);
        if(count($array) > 1){
            $this->setDatabase($array[0]);
            $this->name = $array[1];
            return $this;
        }
        $this->name = $name;
        return $this;
    }

    public function setHasProjectId($hasProjectId) {
        $this->hasProjectId = $hasProjectId;
        return $this;
    }

    public function setHasDateId($hasDateId) {
        $this->hasDateId = $hasDateId;
        return $this;
    }

    public function setClient_id($client_id) {
        $this->client_id = $client_id;
        return $this;
    }

    private function getTableName($tableName, $clientId = null) {
        if ($clientId === null) {
            return $tableName;
        }
        if ($tableName[strlen($tableName) - 1] !== '_') {
            $tableName .= "_";
        }
        return $tableName . $clientId;
    }

    public function getResourceVersion() {
        return $this->resourceVersion;
    }

    public function setResourceVersion($resourceVersion) {
        $this->resourceVersion = $resourceVersion;
        return $this;
    }
    
    /**
     * 
     * @return TableConfig
     */
    public function getTableConfig() {
        return $this->tableConfig;
    }

    public function setTableConfig($tableConfig) {
        $this->tableConfig = $tableConfig;
        return $this;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function setDatabase($database) {
        $this->database = $database;
        return $this;
    }


}
