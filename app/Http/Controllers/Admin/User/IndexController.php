<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Controller;

class IndexController extends Controller {

    public function getIndex() {
        $users = \App\Model\ImportSupport\User::all();
        $this->getView()->addParameter('users', $users);
        
    }


}
