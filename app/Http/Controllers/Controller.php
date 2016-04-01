<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Monkey\View\ViewRender;
use Route;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    private $view;

    public function __construct() {
        $currentRouteAction = Route::currentRouteAction();
        $route = $this->cleanRoute($currentRouteAction);
        $this->view = ViewRender::getInstance($route);
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

}
