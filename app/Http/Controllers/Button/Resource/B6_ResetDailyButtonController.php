<?php

namespace App\Http\Controllers\Button\Resource;


use App\Http\Controllers\Button\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Description of B6_ResetDailyButtonController
 *
 * @author Tomas
 */
class B6_ResetDailyButtonController extends Controller {
    protected function buttonAction() {
        $user = Auth::user();
        if(!$user->can('project.resource.button.reset.daily')){
            return;
        }
        $this->getMessages()->addMessage('Reactivate daily was success');
    }
}
