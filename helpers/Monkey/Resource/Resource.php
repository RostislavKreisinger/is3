<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Resource;

/**
 * Description of Resource
 *
 * @author Tomas
 */
class Resource {
    
    protected $tables = array();
    
    protected $default = null;

    protected $version = 2;

    public function __construct($version = 2) {
        $this->version = $version;
    }

    public function addTable(Table $table) {
        $index = count($this->tables);
        $table->setId($index);
        $this->tables[$index] = $table;
        if($index === 0){
            $this->default = $table;
        }
        return $table;
    }
    
    /**
     * 
     * @return Table
     */
    public function getTables() {
        return $this->tables;
    }
    
    public function getTable($table_id) {
        return $this->tables[$table_id];
    }
    
    public function getDefaultTable() {
        return $this->default;
    }


    
    
}
