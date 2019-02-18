<?php
/**
 * Created by PhpStorm.
 * User: adambardon
 * Date: 12/01/2018
 * Time: 16:08
 */

namespace App\Http\Controllers\OrderAlert;


use App\Helpers\OrderAlert\Detail\ABasePlatform;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\Constants\MonkeyData\Resource\EshopType;
use Monkey\View\View;

class DetailController extends BaseController {

    /**
     * @param $storeId
     * @throws \Exception
     */
    public function getIndex($storeId) {
        $eshop = MDOrderAlertConnections::getOrderAlertConnection()->table('eshop')->where('eshop.id', '=', "$storeId")
            ->leftJoin('currency', 'eshop.currency_id', '=', 'currency.id')
            ->first(['eshop.*','currency.code']);

        if (is_null($eshop)) {
            abort(404);
        }

        $eshop->type = EshopType::getById($eshop->eshop_type_id);

        if ($eshop->eshop_id == '') {
            $eshop->eshop_id = '??';
        }

        foreach($eshop as $key=>$value) {
            if (empty($value)) {
                $eshop->$key = '--';
            }
        }

        View::share('eshop', $eshop);

        $platform = ABasePlatform::getPlatformObject($eshop->eshop_type_id);
        $ordersRaw = $platform->getRawOrders($storeId);
        $orders = array();

        foreach ($ordersRaw as $orderRaw) {
            $orderJSON = json_decode($orderRaw->json, true);
            $order = array();

            if (property_exists($orderRaw, 'title')) {
                $translatedOrderStatus = $platform->translateOrderStatus($orderRaw->title);
                if ($translatedOrderStatus !== null) {
                    $order['statusTitle'] = $translatedOrderStatus;
                }
            }

            foreach ($orderJSON as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $arrayKey => $arrayVal) {
                        // skip objects
                        if (is_integer($arrayKey)) {
                            continue;
                        }
                        else {
                            $order[$arrayKey] = $arrayVal;
                        }
                    }
                } else {
                    $order[$key] = $val;
                }
            }
            array_push($orders, $order);
        }

        $visibleColumns = $platform->getVisibleColumns();
        $omittedColumns = $platform->getOmittedColumns();

        $columnsConfig = '';

        if (!empty($orders)) {
            foreach ($orders[0] as $key => $val) {
                if (in_array($key, $visibleColumns)) {
                    if ($columnsConfig != '') {
                        $columnsConfig .= ', "' . $key . '"';
                    } else {
                        $columnsConfig .= '"' . $key . '"';
                    }
                } else if (in_array($key, $omittedColumns)) {
                    continue;
                } else {
                    if ($columnsConfig != '') {
                        $columnsConfig .= ', { dataField: "' . $key . '", visible: false }';
                    } else {
                        $columnsConfig .= '{ dataField: "' . $key . '", visible: false }';
                    }
                }
            }
        }

        $columnsConfig .= $platform->getColumnsConfig();

        View::share('orders', $orders);
        View::share('columns', $columnsConfig);
    }
}