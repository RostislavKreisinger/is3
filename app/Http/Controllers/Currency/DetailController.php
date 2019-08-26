<?php

namespace App\Http\Controllers\Currency;


use App\Http\Controllers\Controller;
use App\Model\ImportPools\CurrencyEtlCatalog;
use App\Model\ImportPools\CurrencyEtlOrders;

/**
 * Class DetailController
 * @package App\Http\Controllers\Currency
 * @author Tomas
 */
class DetailController extends Controller {
    public function getIndex($currency_id) {
        if (!$this->can('currency.detail')) {
            return $this->redirectToRoot();
        }
        $currencyEtlCatalog = CurrencyEtlCatalog::find($currency_id);
        $orders = CurrencyEtlOrders::where('currency_etl_catalog_id', '=', $currency_id)
                    ->groupBy('project_id')
                    ->select(['project_id', CurrencyEtlOrders::getQuery()->raw('COUNT(`id`) as count')])
                    ->get();
        $this->getView()->addParameter('currency', $currencyEtlCatalog);
        $this->getView()->addParameter('orders', $orders);
    }
}
