<?php

namespace App\Http\Controllers\Admin\Error;

use App\Http\Controllers\Admin\Controller;
use App\Model\ImportSupport\ResourceError;
use Illuminate\Support\Facades\Input;

class CreateController extends Controller {

    public function getIndex($resource_id=null) {
        if(!\Auth::user()->can('project.resource.error.add')){
            return redirect()->back();
        }
        
    }
    
    public function postIndex($resource_id=null) {
        if(!\Auth::user()->can('project.resource.error.add')){
            return redirect()->back();
        }
        $error = new ResourceError();
        $error->btf_for_user = Input::get('btf', '');
        $error->error = Input::get('error', '');
        $error->solution = Input::get('solution', NULL);
        $error->code = Input::get('code', NULL);
        $error->resource_id = $resource_id;
        $error->save();
        
        return redirect()->back();
    }

}
