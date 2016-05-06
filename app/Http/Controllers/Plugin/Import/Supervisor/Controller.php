<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Plugin\Import\Supervisor;

use App\Http\Controllers\Plugin\Controller as BaseController;
use App\Http\Controllers\Plugin\Import\Supervisor\DetailController;
use Monkey\Menu\Menu;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class Controller extends BaseController {



    protected function prepareMenu() {
        $menu = $this->getMenu();

        $menuItem = new Menu(
                "Import 1", \Monkey\action(DetailController::class, ['supervisor_id' => 1])
        );
        $menu->addMenuItem($menuItem);
        
        $menuItem = new Menu(
                "Import 3", \Monkey\action(DetailController::class, ['supervisor_id' => 3])
        );
        $menu->addMenuItem($menuItem);
       
        
        return $menu;
    }

}
