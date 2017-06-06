<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Model\ImportPools\CurrencyEtlCatalog;
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
class IndexController extends Controller {
   
    
    public function getIndex() {

        $hashId = null;
        if($id = Input::get('id', null)){
            $hashId = Strings::id2alpha($id);
        }
        $this->getView()->addParameter('hashId', $hashId);

        View::share('invalidProjects', $this->getInvalidProjects());
        View::share('newProjects', $this->getNewProjects());
        View::share('dailyProjects', $this->getDailyProjects());
        View::share('historyProjects', $this->getHistoryProjects());
        View::share('automattestProjects', $this->getAutomattestProjects());

        $importFlowStatuses = $this->getImportFlowStatuses();

//        vd($importFlowStatuses);
//        vde($importFlowStatuses->filter(function($error) {
//            return isset($error->import);
//        }));
        View::share('importStatuses', $importFlowStatuses->filter(function($error) {
            return isset($error->import);
        }));

        View::share('etlStatuses', $importFlowStatuses->filter(function($error) {
            return isset($error->etl);
        }));

        View::share('calcStatuses', $importFlowStatuses->filter(function($error) {
            return isset($error->calc);
        }));

        View::share('outputStatuses', $importFlowStatuses->filter(function($error) {
            return isset($error->output);
        }));


        $this->getView()->addParameter('autoreportProjects', ProjectRepository::getAutoreportInvalidRecord());
        $this->getView()->addParameter('currencies', CurrencyEtlCatalog::whereNull('currency_names_id')->get());
        $this->getView()->addParameter('stornoOrderStatuses', \App\Model\ImportPools\StornoOrderStatus::getAllUnsolvedStornoOrderStatuses()->get());
    }
    
    
    
    
            
}
