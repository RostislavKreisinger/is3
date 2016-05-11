<?php

namespace App\Http\Controllers\Admin\Error;

use App\Http\Controllers\Admin\Controller;
use App\Model\ImportSupport\ResourceError;
use Illuminate\Support\Facades\Input;

class DetailController extends Controller {

    public function getIndex($error_id) {
        $error = ResourceError::find($error_id);
        $this->getView()->addParameter('error',$error);
    }
    
    public function postIndex($error_id) {
        $error = ResourceError::find($error_id);
        $solution = Input::get('solution', '');
        $error->solution = $solution;
        $error->save();
    }

}
