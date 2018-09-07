<?php

namespace App\Http\Controllers;

use App\Model\ImportSupport\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Monkey\Laravel\v5_4\Illuminate\Routing\AController;
use Redirect;

class BaseAuthController extends AController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     *
     * @var User 
     */
    private $user;


    public static function getMethodAction($method = "getIndex") {
        return static::class . "@" . $method;
    }

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
     * @return static
     */
    public function setUser(User $user) {
        $dth = \Monkey\DateTime\DateTimeHelper::getCloneSelf();
        $userLastVisit = new \Monkey\DateTime\DateTimeHelper($user->last_visit);
        
        if(empty($user->last_visit) || $userLastVisit->getTimestamp() < ($dth->getTimestamp()-60) ){
            $user->last_visit = $dth->mysqlFormat();
            $user->save();
            $visit = new \App\Model\ImportSupport\Visit();
            $visit->user_id = $user->id;
            $visit->visited_at = $dth->mysqlFormat();
            $visit->save();
        }
        
       
        
        $this->user = $user;
        return $this;
    }

    
    public static function routeMethod($methodName) {
        return static::class . "@{$methodName}";
    }

}
