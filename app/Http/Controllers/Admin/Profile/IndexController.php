<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Admin\Controller;
use Auth;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller {

    public function getIndex() {

        // $this->getView()->getMessages()->addError(new \Monkey\View\Message\Message('test'));
        
        $this->getView()->addParameter('user', $this->getUser());
    }

    public function postIndex() {
        
        
        
        $user = Auth::user();       
        $credentials = array(
            'email' => $user->email,
            'password' => Input::get('password-old')
            );

        if (!Auth::guard(null)->attempt($credentials)) {
            $this->getView()->getMessages()->addError('Old password is wrong');
            return redirect()->back();
        }
        
        if (Input::get('password-new') != Input::get('password-new-2') ) {
            $this->getView()->getMessages()->addError('New passwords is not same');
            return redirect()->back();
        }
        
        if (strlen(Input::get('password-new')) < 6 ) {
            $this->getView()->getMessages()->addError('Password must be longer then 6 characters');
            return redirect()->back();
        }
        
        $this->getUser()->password = bcrypt(Input::get('password-new'));
        $this->getUser()->save();
        $this->getView()->getMessages()->addMessage('Password was changed');
        return redirect()->back();
    }
}
    