<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller {

    public function getIndex() {


        $this->getView()->addParameter('user', $this->getUser());
    }

    public function postIndex() {
        /*if (\Illuminate\Support\Facades\Hash::check(Input::get('password-old'), $this->getUser()->password)) {
            vde('not same as old');
            return redirect()->back();
        }*/
        /*
        if (Input::get('password-new') != Input::get('password-new-2')) {
            vde('not same');
            return redirect()->back();
        }

        $this->getUser()->password = \Illuminate\Support\Facades\Hash::make(Input::get('password_new'));
        $this->getUser()->save();
*/
        return redirect()->back();
    }
}
    