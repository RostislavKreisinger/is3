<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Admin\Controller;

class IndexController extends Controller {

    public function getIndex() {


        $this->getView()->addParameter('user', $this->getUser());
    }

    public function postIndex() {
        
        
        return redirect()->back();
    }

}
