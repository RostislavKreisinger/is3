<?php

namespace App\Http\Controllers\Admin\Error;

use App\Http\Controllers\Admin\Controller;
use App\Model\ImportSupport\ResourceError;
use Illuminate\Support\Facades\Input;

class CreateController extends Controller {

    public function getIndex($resource_id=null) {
        
    }
    
    public function postIndex($resource_id=null) {
        $error = new ResourceError();
        $error->btf_for_user = Input::get('btf', '');
        $error->error = Input::get('error', '');
        $error->solution = Input::get('solution', NULL);
        $error->resource_id = $resource_id;
        $error->save();
    }

}
