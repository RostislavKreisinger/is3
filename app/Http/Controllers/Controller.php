<?php

namespace App\Http\Controllers;

use App\Model\ImportSupport\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Monkey\Menu\Menu;
use Monkey\Menu\MenuList;
use Monkey\View\ViewRender;
use Redirect;
use Route;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    private $view;
    
    /**
     *
     * @var User 
     */
    private $user;
    
    /**
     *
     * @var Menu 
     */
    private $menu;

    public function __construct() {
        if(Auth::check()){
            $this->setUser(User::find(Auth::user()->id));
        }
        
        $currentRouteAction = Route::currentRouteAction();
        $route = $this->cleanRoute($currentRouteAction);
        $this->view = ViewRender::getInstance($route);
        $this->menu = new MenuList();
    }

    public function callAction($method, $parameters) {
        $result = parent::callAction($method, $parameters);
        if ($result === null) {
            $result = $this->getView()->render();
        }

        return $result;
    }

    public static function routeMethod($methodName) {
        return static::class . "@{$methodName}";
    }

    protected function cleanRoute($route) {
        $route = str_replace('App\\Http\\Controllers\\', '', $route);
        list($folder, $file) = explode('@', $route);

        $folder = strtolower(str_replace('Controller', '', $folder));
        $folder = explode('\\', $folder);

        $file = $this->setCalledMethodName($file);

        $route = $folder;
        $route[] = $file;
        return $route;
    }

    protected function setCalledMethodName($methodName) {
        $methodName = preg_replace('/^get/', '', $methodName);
        $methodName = preg_replace('/^post/', '', $methodName);
        $methodName = preg_replace('/^action/', '', $methodName);
        return strtolower($methodName);
    }
    
    /**
     * 
     * @return ViewRender
     */
    protected function getView() {
        return $this->view;
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



}
