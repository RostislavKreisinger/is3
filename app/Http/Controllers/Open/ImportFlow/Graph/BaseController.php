<?php

namespace App\Http\Controllers\Open\ImportFlow\Graph;


use App\Http\Controllers\BaseViewController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Monkey\View\View;

class BaseController extends BaseViewController {

    public function getIndex() {
        $fontSize = Input::get("fontSize", '1em');
        View::share("fontSize", $fontSize);
    }

}