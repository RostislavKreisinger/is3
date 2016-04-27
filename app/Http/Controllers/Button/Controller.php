<?php

namespace App\Http\Controllers\Button;

use App\Http\Controllers\BaseAuthController;


abstract class Controller extends BaseAuthController {

    public function __construct() {
        parent::__construct();
    }
    
    public function getIndex() {
        $redirect = redirect()->back();
        call_user_func_array([$this, 'buttonAction'], func_get_args());
        return $redirect;
    }
    
    
    abstract protected function buttonAction();

    
}
