<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Model\ImportPools\CurrencyEtlCatalog;
use DB;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class DetailController extends Controller {

    public function getIndex($currency_id) {
        if (!$this->can('currency.detail')) {
            return $this->redirectToRoot();
        }
        $currencyEtlCatalog = CurrencyEtlCatalog::find($currency_id);
        $orders = DB::connection('mysql-select-import')
                    ->table('monkeydata_pools.currency_etl_orders as ceo')
                    ->where('currency_etl_catalog_id', '=', $currency_id)
                    ->groupBy('project_id')
                    ->select(['project_id', DB::raw('COUNT(`id`) as count')])
                    ->get()
                ;
        $this->getView()->addParameter('currency', $currencyEtlCatalog);
        $this->getView()->addParameter('orders', $orders);
    }

}
