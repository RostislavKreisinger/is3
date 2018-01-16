<?php
/**
 * Created by PhpStorm.
 * User: adambardon
 * Date: 12/01/2018
 * Time: 16:08
 */

namespace App\Http\Controllers\OrderAlert;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\View\View;

class DetailController extends BaseController {
    public function getIndex($storeId) {
//        $a = Input::get("storeId");
//        vd($a);
        $data = MDOrderAlertConnections::getOrderAlertConnection()->table('eshop')->where(function(Builder $where) use ($storeId) {
            $where->orWhere('eshop_id', '=', "$storeId");
        })
            ->leftJoin('currency', 'eshop.currency_id', '=', 'currency.id')
            ->leftJoin('eshop_type', 'eshop.eshop_type_id', '=', 'eshop_type.id')
            ->get(['eshop.*','currency.code', 'eshop_type.type']);
//            ->joinWhere('currency', 'currency_id', '=', 'id')->get();
//            ->get();

        if (!empty($data)) {
            View::share("eshop", $data[0]);
        }

        $ordersRaw = MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_".$storeId)->get();
        $orders = array();
        foreach ($ordersRaw as $orderRaw) {
            $orderJSON = json_decode($orderRaw->json, true);
            $order = array();
//            vd($orderJSON);
            foreach ($orderJSON as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $arrayKey => $arrayVal) {
//                        array_push($order, $arrayItem);
                        $order[$arrayKey] = $arrayVal;
                    }
                }
                else {
//                    array_push($order, ($key => $val);
                    $order[$key] = $val;
                }
            }
            array_push($orders, $order);
        }
        View::share("orders", $orders);
        View::share("columns", "");
    }
}