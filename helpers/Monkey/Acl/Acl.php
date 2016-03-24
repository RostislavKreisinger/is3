<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Acl;

/**
 * Description of Acl
 *
 * @author Tomas
 */
class Acl {
    
    private $aclList = array();
    
    public function hasAcl($aclString) {
        foreach($this->getAclList() as $acl){
            $regex ="/^".str_replace(['.', '*'], ['\.', '.+'], $acl)."(\..+)*$/";
            if(preg_match($regex, $aclString)){
                return true;
            }
        }
        return false;
    }
    
    public function getAclList() {
        return $this->aclList;
    }

    public function setAclList($aclList) {
        $this->aclList = $aclList;
    }

    public function addAcl($acl) {
        $this->aclList[] = $acl;
    }


}
