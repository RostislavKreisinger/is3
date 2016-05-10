<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Acl\AclList;

/**
 * Description of Acl
 *
 * @author Tomas
 */
class Acl {

    /**
     *
     * @var Acl 
     */
    private $aclList = array();
    
    private $path;

    public function __construct($path = null) {
    }
    
    

    public function addAcl($aclArray, $path = null) {
        if($path=== null){
            $path = $aclArray;
        }
        
        if(empty($aclArray)){
            $this->path = $path;
            return;
        }
        if (!is_array($aclArray)) {
            $aclArray = explode('.', $aclArray);
        }
        $aclArray = array_values($aclArray);
        $first = $aclArray[0];
        unset($aclArray[0]);
        if (!isset($this->aclList[$first])) {
            $this->aclList[$first] = new Acl();
        }
        $this->aclList[$first]->addAcl($aclArray, $path);
    }

    public function isNode() {
        return !( (bool) count($this->aclList) );
    }
    public function getAclList() {
        return $this->aclList;
    }

    public function getPath() {
        return $this->path;
    }

    public function setAclList(Acl $aclList) {
        $this->aclList = $aclList;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    
    public function render() {
        $text = "<ul>";
        foreach ($this->getAclList() as $key => $item) {
            $text .= "<li>";
            $text .= "<span>{$key}</span>";
            if(!$item->isNode()){
                $text .= $item->render();
            }
            $text .= "</li>";
        }
        $text .= "</ul>";
        return $text;
    }


}
