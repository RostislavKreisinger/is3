<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Database;

/**
 * Description of Snippet
 *
 * @author Tomáš
 */
class Snippet {
    public $content;
    public $name;
    public $tabTrigger;
    
    public function __construct($content, $name = '', $tabTrigger = '') {
        if($name == ''){
            $name = $content;
        }
        if($tabTrigger == ''){
            $tabTrigger = $name;
        }
        $this->content = $content;
        $this->name = $name;
        $this->tabTrigger = $tabTrigger;
    }

}
