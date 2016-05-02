<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Resource\TableConfig;

/**
 * Description of TableConfig
 *
 * @author Tomas
 */
class TableConfig implements IJsonSerialize{
    
    /**
     *
     * @var Column
     */
    private $columns = array();
    
    
    public function __construct($serialize = null) {
        $this->jsonDeserialize($serialize);
    }
    
    public function getColumns() {
        return $this->columns;
    }

    public function setColumns($columns) {
        $this->columns = $columns;
        return $this;
    }
    /**
     * 
     * @param Column $column
     * @return TableConfig
     */
    public function addColumn(Column $column) {
        $this->columns[] = $column;
        return $this;
    }
    
    /**
     * 
     * @param Column $column
     * @return TableConfig
     */
    public function addDefaultColumn(Column $column) {
        foreach ($this->getColumns() as $col) {
            if($col->getName() == $column->getName()){
                return $this;
            }
        }
        $this->addColumn($column);
        return $this;
    }

    public function jsonSerialize() {
        $array = [];
        foreach ($this->getColumns() as $column) {
            $array[] = $column->jsonSerialize();
        }
        return $array;
    }

    public function jsonDeserialize($serialize) {
        if($serialize === null){
            return;
        }
        $object = json_decode($serialize);
        $array = (array)$object;
        foreach ($array as $value) {
            $this->addColumn(new Column($value));
        }
    }

}
