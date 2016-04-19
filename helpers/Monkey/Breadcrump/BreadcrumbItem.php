<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Monkey\Breadcrump;

/**
 * Description of Breadcrumps
 *
 * @author Tomas
 */
class BreadcrumbItem {
    
    /**
     *
     * @var string 
     */
    private $code;
    
    /**
     *
     * @var string 
     */
    private $btf;
    
    /**
     *
     * @var string 
     */
    private $url;
    
    
    public function __construct($code, $btf = null, $url = null) {
        $this->code = $code;
        $this->btf = $btf;
        $this->url = $url;
    }

    
    public function getCode() {
        return $this->code;
    }

    public function getBtf() {
        return $this->btf;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setBtf($btf) {
        $this->btf = $btf;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }


}
