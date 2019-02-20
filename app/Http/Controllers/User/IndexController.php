<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\ApiController;
use App\Model\User;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends ApiController {
    protected $endpoint = 'base/users';

    public function getIndex() {
        if(!$this->can('user.list')){
            return $this->redirectToRoot();
        }
        
        $users = User::whereNull('deleted_at')->limit(40)->orderBy('created_at', 'desc')->get();
        $this->getView()->addParameter('users', $users);
    }
}