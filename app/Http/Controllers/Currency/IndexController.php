<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Currency;

use App\Http\Controllers\Controller;
use App\Model\Currency;
use App\Model\ImportPools\CurrencyEtlCatalog;
use App\Model\ImportPools\CurrencyEtlOrders;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {

    public function getIndex() {
        if (!$this->can('currency.list')) {
            return $this->redirectToRoot();
        }
        $currencies = CurrencyEtlCatalog::getToResolve();
        $currencyList = Currency::all();
        $this->getView()->addParameter('currencies', $currencies);
        $this->getView()->addParameter('currencyList', $currencyList);
    }

    public function postIndex() {
        $currency_name_id = Input::get('currency_name_id');
        $currency_etl_catalog_id = Input::get('currency_etl_catalog_id');

        // 
        $orders = CurrencyEtlOrders::where('currency_etl_catalog_id', '=', $currency_etl_catalog_id)
                ->where('active', '=', 1)
                ->get()
        ;
        $projects = array();
        foreach ($orders as $order) {
            if (!isset($projects[$order->project_id])) {
                $projects[$order->project_id] = array();
            }
            $projects[$order->project_id][] = $order->order_id;
        }



        foreach ($projects as $project_id => $orders) {
            $client_id = DB::connection('mysql-select-app')
                            ->table('project as p')
                            ->select('c.id')
                            ->join('client as c', 'c.user_id', '=', 'p.user_id')
                            ->where('p.id', '=', $project_id)->first()->id;

            $eshopTable = "f_eshop_order_" . $client_id;
            try{
                DB::transaction(function() use ($projects, $currency_name_id, $currency_etl_catalog_id, $eshopTable, $orders) {
                    DB::connection('mysql-import-dw')
                            ->table($eshopTable)
                            ->whereIn('id', $orders)
                            ->update(array('currency_id' => $currency_name_id));

                    DB::connection('mysql-import-pools')
                            ->table('currency_etl_orders')
                            ->whereIn('order_id', $orders)
                            ->update(array('active' => 0));
                });
            }  catch (Exception $e){
                
            }
        }

        $CurrencyEtlCatalog = CurrencyEtlCatalog::find($currency_etl_catalog_id);
        $CurrencyEtlCatalog->currency_names_id = $currency_name_id;
        $CurrencyEtlCatalog->save();
        
        $this->getView()->getMessages()->addMessage('Currency was assigned.');
        return redirect()->action(self::routeMethod('getIndex'));
    }

}
