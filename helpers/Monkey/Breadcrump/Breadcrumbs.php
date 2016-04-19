<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Breadcrump;

use Exception;
use Iterator;

/**
 * Description of Breadcrumps
 *
 * @author Tomas
 */
class Breadcrumbs implements Iterator, \Countable {
    
    private $position = 0;


    /**
     *
     * @var BreadcrumbItem[] 
     */
    private $list = array();
    

    
    public function getList() {
        return $this->list;
    }
    
    public function setList(array $list) {
        $this->list = $list;
        return $this;
    }
    
    /**
     * 
     * @param BreadcrumbItem $item
     * @return BreadcrumbItem
     */
    public function addBreadcrumbItem(BreadcrumbItem $item) {
        $this->list[] = $item;
        return $item;
    }
    
    public function getItem($key) {
        foreach ($this->list as $item){
            if($item->getCode() == $key){
                return $item;
            }
        }
        throw new Exception("Undefined breadcrumb with code '{$key}'");
    }

    

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->list[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->list[$this->position]);
    }

    public function count($mode = 'COUNT_NORMAL') {
        return count($this->list);
    }

}
