<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Homepage;

use App\Model\ImportPools\CurrencyEtlCatalog;
use App\Model\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Monkey\Helpers\Strings;
use Monkey\ImportSupport\InvalidProject\ProjectRepository;
use Monkey\View\View;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class Importv2Controller extends BaseController {


    public function getIndex() {

        View::share('dailyProjects', $this->getDailyProjects());
        View::share('historyProjects', $this->getHistoryProjects());
        View::share('automattestProjects', $this->getAutomattestProjects());



        $this->getView()->addParameter('autoreportProjects', ProjectRepository::getAutoreportInvalidRecord());
        $this->getView()->addParameter('currencies', CurrencyEtlCatalog::whereNull('currency_names_id')->get());
        $this->getView()->addParameter('stornoOrderStatuses', \App\Model\ImportPools\StornoOrderStatus::getAllUnsolvedStornoOrderStatuses()->get());
    }


}
