<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Resource\TableConfig;

use Illuminate\Database\Query\Builder;
use ReflectionObject;
use ReflectionProperty;
use stdClass;

/**
 * Description of Column
 *
 * @author Tomas
 */
class Column implements IJsonSerialize {

    /**
     * @public
     * @var string 
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $rawName = null;
    
    /**
     * @public
     * @var string 
     */
    protected $orderBy = null;
    
    /**
     * @public
     * @var string 
     */
    protected $where = null;

    public function __construct($serialize = null) {
        $this->jsonDeserialize($serialize);
    }
    
    public function updateQueryBuilder(Builder &$builder) {
        if($this->getOrderBy() !== null){
            $builder->orderBy($this->getName(), $this->getOrderBy());
        }
        
        if($this->getWhere() !== null){
            $name = $this->getName();

            if($this->getRawName()) {
                $name = $this->getRawName();
            }

            $builder->whereRaw("{$name} {$this->getWhere()}");
        }
    }
    

    public function jsonSerialize() {
        $target = new stdClass();
        $sourceReflection = new ReflectionObject($this);
        $sourceProperties = $sourceReflection->getProperties(ReflectionProperty::IS_PROTECTED);

        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($this);
            
            if( strpos($sourceProperty->getDocComment(), "@public") === false ){
                continue;
            }
            if($value === null){
                continue;
            }
            $target->{$name} = $value;
        }
        return $target;
    }

    public function jsonDeserialize($serialize) {
        if($serialize === null){
            return;
        }
        $object = $serialize;
        if(!is_object($serialize)){
            $object = json_decode($serialize);
        }
        $array = (array)$object;
        foreach($array as $key => $value){
            $this->{$key} = $value;
        }
    }
        
    public function getName() {
        return $this->name;
    }

    public function getOrderBy() {
        return $this->orderBy;
    }

    public function getWhere() {
        return $this->where;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function setWhere($where) {
        $this->where = $where;
        return $this;
    }

    /**
     * @return string
     */
    public function getRawName()
    {
        return $this->rawName;
    }

    /**
     * @param string $rawName
     * @return Column
     */
    public function setRawName($rawName)
    {
        $this->rawName = $rawName;
        return $this;
    }



}
