<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\ImportSupport\Resource\Button;

/**
 * Description of ButtonList
 *
 * @author Tomas
 */
class ButtonList implements \ArrayAccess {
    /**
     *
     * @var BaseButton[] 
     */
    private $list = array();
    
    
    
    
    public function __construct() {
        ;
    }
    
    /**
     * 
     * @return BaseButton[]
     */
    public function getList() {
        return $this->list;
    }
    
    public function getTestButtons() {
        $result = array();
        foreach($this->getList() as $button){
            if($button->getType() == BaseButton::BUTTON_TYPE_TEST){
                $result[] = $button; 
            }
        }
        return $result;
    }
    
    public function getRepairButtons() {
        $result = array();
        foreach($this->getList() as $button){
            if($button->getType() == BaseButton::BUTTON_TYPE_REPAIR){
                $result[] = $button; 
            }
        }
        return $result;
    }
    
    public function getDeleteButtons() {
        $result = array();
        foreach($this->getList() as $button){
            if($button->getType() == BaseButton::BUTTON_TYPE_DELETE){
                $result[] = $button; 
            }
        }
        return $result;
    }

    public function setList(BaseButton $list) {
        $this->list = $list;
    }
    
    public function getButton($code) {
        return $this->list[$code];
    }
    
    public function addButton(BaseButton $list) {
        $this->list[$list->getCode()] = $list;
        return $this;
    }

    
    public function offsetExists($offset) {
        return isset($this->list[$offset]);
    }

    public function offsetGet($offset) {
        return $this->list[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->list[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->list[$offset]);
    }

}
