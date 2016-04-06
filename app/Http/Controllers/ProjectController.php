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
class ProjectController extends Controller {
    
    public function index() {
        $user = User::find(1);    
    }
    
    public function show($id) {
        vd($id);
        $user = User::find(1);    
    }
}
