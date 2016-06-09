<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\StornoOrderStatus;

use App\Http\Controllers\Controller;
use App\Model\ImportPools\StornoOrderStatus;
use Illuminate\Support\Facades\Input;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class IndexController extends Controller {

    public function getIndex() {
        if (!$this->can('storno-order-statuses.list')) {
            return $this->redirectToRoot();
        }
        $stornoOrderStatuses = StornoOrderStatus::where('active', '=', 0)->get();

        $this->getView()->addParameter('stornoOrderStatuses', $stornoOrderStatuses);
    }

    public function postIndex() {
        
        $storno_order_status_id = Input::get('storno_order_status_id');
        $active = Input::get('active', 0);
        
        $sos = StornoOrderStatus::find($storno_order_status_id);
        $sos->active = $active;
        $sos->save();

        $this->getView()->getMessages()->addMessage('Storno order status was updated.');
        return redirect()->action(self::routeMethod('getIndex'));
    }

}
