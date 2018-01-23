<?php
/**
 * Created by PhpStorm.
 * User: adambardon
 * Date: 12/01/2018
 * Time: 16:08
 */

namespace App\Http\Controllers\OrderAlert;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Query\Builder;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\Constants\MonkeyData\Resource\EshopType;
use Monkey\View\View;

class IndexController extends BaseController {
    public function getIndex() {
        $search = $originSearch =  Input::get('search', null);
        $eshops = MDOrderAlertConnections::getOrderAlertConnection()->table('eshop')->where(function(Builder $where) use ($originSearch) {
            $where->orWhere('eshop_id', 'like', "%$originSearch%")
                ->orWhere('eshop_name', 'like', "%$originSearch%")
                ->orWhere('email', 'like', "%$originSearch%")
                ->orWhere('owner', 'like', "%$originSearch%");
        })
            // ->leftJoin('eshop_type', 'eshop.eshop_type_id', '=', 'eshop_type.id')
            //->get(['eshop.*', 'eshop_type.type']);
        ->get(['eshop.*']);

        if (count($eshops) == 1) {
            return Redirect::action(DetailController::routeMethod('getIndex'), ['storeId'=>$eshops[0]->eshop_id]);
        }

        foreach ($eshops as $eshop) {
            $eshop->type = EshopType::getById($eshop->eshop_type_id);
            $eshop->link = \URL::action(DetailController::routeMethod("getIndex"), ['storeId'=>$eshop->eshop_id]);
        }
        View::share('search', $search);
        View::share('eshops', $eshops);
    }
}