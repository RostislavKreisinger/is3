<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Project\DetailController;
use App\Model\ImportSupport\User;
use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Monkey\Breadcrump\Breadcrumbs;
use Monkey\Breadcrump\BreadcrumbItem;
use Monkey\Menu\Menu;
use Monkey\Menu\MenuList;
use Monkey\View\View;
use Monkey\View\ViewRender;
use Redirect;
use Route;

class BaseViewController extends BaseAuthController {

  

    private $view;
    
    /**
     *
     * @var MenuList 
     */
    private $menu;
    
    private $breadcrumbs;


    public function __construct() {
        parent::__construct();
        $currentRouteAction = Route::currentRouteAction();
        $route = $this->cleanRoute($currentRouteAction);
        $this->view = ViewRender::getInstance($route);
        
        View::share('breadcrumbs', $this->getBreadcrumbs());
        $this->initMenu();
    }
    
    private function initMenu() {
        // clearQueryLog();
        $menu = $this->prepareMenu();
        //queryLog();
        $this->getView()->addParameter('menu', $menu);
        View::share('menu', $menu);
        $this->setMenu($menu);
    }

    /**
     * 
     * @return MenuList
     */
    protected function prepareMenu() {
        return $this->getMenu(); 
    }
    
    public function callAction($method, $parameters) {
        $this->breadcrumbBeforeAction($parameters);
        $result = parent::callAction($method, $parameters);
        $this->breadcrumbAfterAction($parameters);
        
        
        if ($result === null) {
            $result = $this->getView()->render();
        }

        return $result;
    }

    

    protected function cleanRoute($route) {
        $route = str_replace('App\\Http\\Controllers\\', '', $route);
        list($folder, $file) = explode('@', $route);

        $folder = strtolower(str_replace('Controller', '', $folder));
        $folder = explode('\\', $folder);

        $file = $this->setCalledMethodName($file);

        $route = $folder;
        // $route[] = $file;
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
    
    /**
     * 
     * @return MenuList
     */
    public function getMenu() {
        if($this->menu === null){
            $this->menu = new MenuList();
        }
        return $this->menu;
    }

    /**
     * 
     * @param MenuList $menu
     * @return Controller
     */
    public function setMenu(MenuList $menu) {
        $this->menu = $menu;
        return $this;
    }

    /**
     * 
     * @return Breadcrumbs
     */
    protected function getBreadcrumbs() {
        if($this->breadcrumbs == null){
            $this->breadcrumbs = new Breadcrumbs();
        }
        return $this->breadcrumbs;
    }

    
    /**
     * 
     * @param array $parameters
     * @return Breadcrumbs
     */
    protected function breadcrumbBeforeAction($parameters = array()) {
        $breadcrumbs = $this->getBreadcrumbs();
        $breadcrumbs->addBreadcrumbItem(new BreadcrumbItem('home', 'Home', \Monkey\action(IndexController::class)));
        return $breadcrumbs;
    }
    /**
     * 
     * @param array $parameters
     * @return Breadcrumbs
     */
    protected function breadcrumbAfterAction($parameters = array()) {
        $breadcrumbs = $this->getBreadcrumbs();
        return $breadcrumbs;
    }


}
