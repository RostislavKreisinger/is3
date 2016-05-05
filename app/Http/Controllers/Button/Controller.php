<?php

namespace App\Http\Controllers\Button;

use App\Http\Controllers\BaseAuthController;


abstract class Controller extends BaseAuthController {

    public function __construct() {
        parent::__construct();
    }
    
    protected function getRedirect() {
        return redirect()->back();
    }
    
    public function getIndex() {
        $redirect = $this->getRedirect();
        call_user_func_array([$this, 'buttonAction'], func_get_args());
        return $redirect;
    }
    
    
    abstract protected function buttonAction();

    
}
