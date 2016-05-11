<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\User\IndexController as UserIndexController;
use App\Http\Controllers\Admin\Profile\IndexController as ProfileIndexController;
use App\Http\Controllers\Admin\Error\IndexController as ErrorIndexController;
use App\Http\Controllers\BaseViewController;
use Monkey\Menu\Menu;

class Controller extends BaseViewController {
    
    
    protected function prepareMenu() {
        $menu = parent::prepareMenu();
        
        $menu->addMenuItem(new Menu('Profile', \Monkey\action(ProfileIndexController::class)));
        $menu->addMenuItem(new Menu('Errors', \Monkey\action(ErrorIndexController::class)));
        
        if( $this->getUser()->isAdmin() ){
            $menu->addMenuItem(new Menu('Users', \Monkey\action(UserIndexController::class)));
        }
        
        
        return $menu;
    }
}
