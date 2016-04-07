<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Menu;

/**
 * Description of Menu
 *
 * @author Tomas
 */
class Menu extends MenuList {
    
    private $name;
    
    private $link;
    
    private $opened = false;
    
    public function __construct($name, $link) {
        parent::__construct();
        $this->setName($name);
        $this->setLink($link);
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getLink() {
        return $this->link;
    }
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    
    public function setLink($link) {
        $this->link = $link;
        return $this;
    }
    
    public function getOpened() {
        return $this->opened;
    }

    public function setOpened($opened) {
        $this->opened = $opened;
        return $this;
    }


    
}
