<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\User\IndexController;
use App\Http\Controllers\BaseViewController;
use Monkey\Menu\Menu;

class Controller extends BaseViewController {
    
    
    protected function prepareMenu() {
        $menu = parent::prepareMenu();
        
        $menu->addMenuItem(new Menu('Users', \Monkey\action(IndexController::class)));
        
        
        return $menu;
    }
}
