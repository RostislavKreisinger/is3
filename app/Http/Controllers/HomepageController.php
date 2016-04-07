<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Model\User;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class HomepageController extends Controller {
    
    public function getIndex() {
        $user = User::find(1); 
        $user->getClient();
    }
}
