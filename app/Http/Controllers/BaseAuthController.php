<?php

namespace App\Http\Controllers;

use App\Model\ImportSupport\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Redirect;

class BaseAuthController extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     *
     * @var User 
     */
    private $user;



    public function __construct() {
        if(Auth::check()){
            $this->setUser(Auth::user());
            $this->getUser()->getAclManager();
        }
    }
    
    
    protected function can($acl) {
        return $this->getUser()->can($acl);
    }
    
    protected function redirectToRoot() {
        return Redirect::to('/');
    }
    
    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 
     * @param User $user
     * @return Controller
     */
    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }

    
    public static function routeMethod($methodName) {
        return static::class . "@{$methodName}";
    }

}
