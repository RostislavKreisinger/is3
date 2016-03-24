<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController {
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;
    
    private $view;


    public function __construct() {
        
        
    }
    
    public function callAction($method, $parameters) {
        $result = parent::callAction($method, $parameters);
        if( $result === null){
            $result = 'EMPTY result';
        }
        
        return $result;
    }
    
    
    public static function routeMethod($methodName) {
        return static::class."@{$methodName}";
    }
    
}
