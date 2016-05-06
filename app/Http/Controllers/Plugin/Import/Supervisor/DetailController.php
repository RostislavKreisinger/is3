<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Plugin\Import\Supervisor;


/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {

    public function getIndex($supervisor_id) {
        
        $this->getView()->addParameter('supervisorId', $supervisor_id);
    }

   

}
