<?php

namespace App\Http\Controllers\OrderAlert\Search;

use App\Http\Controllers\OrderAlert\DetailController;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDOrderAlertConnections;
use Illuminate\Database\Query\Builder;
use Monkey\View\View;

class EshopController extends BaseController {

    public function getIndex() {
        $search = $originSearch =  Input::get('search', null);
//        $data = MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_".$storeId)->get();
        $eshops = MDOrderAlertConnections::getOrderAlertConnection()->table('eshop')->where(function(Builder $where) use ($originSearch) {
                                                                                                $where->orWhere('eshop_id', 'like', "%$originSearch%")
                                                                                                      ->orWhere('eshop_name', 'like', "%$originSearch%")
                                                                                                      ->orWhere('email', 'like', "%$originSearch%")
                                                                                                      ->orWhere('owner', 'like', "%$originSearch%");
                                                                                            })
                                                                                            ->leftJoin('eshop_type', 'eshop.eshop_type_id', '=', 'eshop_type.id')
                                                                                            ->get(['eshop.*', 'eshop_type.type']);
//        \URL::action(DetailController::routeMethod("getIndex"), ['storeId'=>123]);
        foreach ($eshops as $eshop) {
            $eshop->link = \URL::action(DetailController::routeMethod("getIndex"), ['storeId'=>$eshop->eshop_id]);
        }
        View::share('search', $search);
        View::share('eshops', $eshops);
//        vde($eshops);
    }

}

