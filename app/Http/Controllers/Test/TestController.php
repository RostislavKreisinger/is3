<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\BaseAuthController;
use Monkey\View\Message\MessageList;
use Session;


class TestController extends BaseAuthController {

    
    
    
    public function getIndex() {
        Session::put('test', 'test');
        Session::save();
        vd(Session::has('test'));
        vd(Session::get('test'));
        vd(Session::all());
        return '<a href="http://localhost/import-support-v3.monkeydata.com/public/test/test1">neco</a>';
    }
    
    
    public function getTest1() {
        vde(session('test') );
        vd(Session::has('test'));
        vd(Session::get('test'));
        vde(Session::all());
    }

    
    public function getTest2() {
        
    }


    
}
