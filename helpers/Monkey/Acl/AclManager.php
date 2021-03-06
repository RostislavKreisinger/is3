<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Acl;


/**
 * Description of AclManager
 *
 * @author Tomas
 */
class AclManager {

    /**
     *
     * @var IAclUser
     */
    protected $user;

    /**
     *
     * @var Acl
     */
    protected $alc;

    public function __construct(IAclUser $user, Acl $acl) {
        $this->setUser($user);
        $this->setAlc($acl);
        $this->init();
    }
    
    public function init() {
        $aclList = $this->getUser()->getAcl();
        foreach( $aclList as $acl ){
            $this->getAlc()->addAcl($acl->key);
        }
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(IAclUser $user) {
        $this->user = $user;
        return $this;
    }

    public function getAlc() {
        return $this->alc;
    }
    
    public function hasAcl($aclString) {
        
        return $this->getAlc()->hasAcl($aclString);
    }

    public function setAlc(Acl $alc) {
        $this->alc = $alc;
        return $this;
    }

}
