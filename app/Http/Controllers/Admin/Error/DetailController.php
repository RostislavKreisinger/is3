<?php

namespace App\Http\Controllers\Admin\Error;

use App\Http\Controllers\Admin\Controller;
use App\Model\ImportSupport\ResourceError;
use Illuminate\Support\Facades\Input;

class DetailController extends Controller {

    public function getIndex($error_id) {
        if(!\Auth::user()->can('project.resource.error.edit')){
            return redirect()->back();
        }
        $error = ResourceError::find($error_id);
        $resource = $error->getResource();
        $this->getView()->addParameter('error', $error);
        $this->getView()->addParameter('resource', $resource);
    }
    
    public function postIndex($error_id) {
        if(!\Auth::user()->can('project.resource.error.edit')){
            return redirect()->back();
        }
        $error = ResourceError::find($error_id);
        $solution = Input::get('solution', null);
        $error->solution = $solution;
        $error->code = Input::get('code', null);
        $error->save();
        return redirect()->back();
    }

}
