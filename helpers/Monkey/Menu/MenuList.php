<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Menu;

/**
 * Description of MenuList
 *
 * @author Tomas
 */
class MenuList {

    private $list;
    
    public function __construct() {
        
    }

    public function getList() {
        return $this->list;
    }

    public function setList($list) {
        $this->list = $list;
        return $this;
    }

    public function hasSubList() {
        return count($this->getList()) > 0;
    }
    
    public function addMenuItem(Menu $menuItem) {
        $this->list[] = $menuItem;
    }

}
