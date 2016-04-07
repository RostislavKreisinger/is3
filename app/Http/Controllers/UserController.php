<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\User;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class UserController extends Controller {

    public function getIndex() {
        if(!$this->can('user.list')){
            return $this->redirectToRoot();
        }
        
        $users = \App\Model\User::whereNull('deleted_at')->limit(40)->orderBy('created_at', 'desc')->get();
        $this->getView()->addParameter('users', $users);
        
        
    }

}
