<?php
/**
 * Created by PhpStorm.
 * User: adambardon
 * Date: 12/01/2018
 * Time: 16:08
 */

namespace App\Http\Controllers\OrderAlert;


use Illuminate\Database\Query\Builder;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\Constants\MonkeyData\Resource\EshopType;
use Monkey\View\View;

class DetailController extends BaseController {
    public function getIndex($storeId) {
        $eshop = MDOrderAlertConnections::getOrderAlertConnection()->table('eshop')->where('eshop_id', '=', "$storeId")
            ->leftJoin('currency', 'eshop.currency_id', '=', 'currency.id')
            // ->leftJoin('eshop_type', 'eshop.eshop_type_id', '=', 'eshop_type.id')
            ->first(['eshop.*','currency.code']);

        if (!is_null($eshop)) {
            $eshop->type = EshopType::getById($eshop->eshop_type_id);
            View::share("eshop", $eshop);

            $ordersRaw = MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
                ->leftJoin('d_order_status', 'f_order_eshop_' . $storeId . '.status_id', '=', 'd_order_status.id')
                ->get(['f_order_eshop_' . $storeId . '.*', 'd_order_status.status', 'd_order_status.title']);

            $orders = array();
            foreach ($ordersRaw as $orderRaw) {
                $orderJSON = json_decode($orderRaw->json, true);
                $order = array();
                $order['statusCode'] = $orderRaw->status;
                $order['statusTitle'] = $orderRaw->title;

                foreach ($orderJSON as $key => $val) {
                    if (is_array($val)) {
                        foreach ($val as $arrayKey => $arrayVal) {
                            $order[$arrayKey] = $arrayVal;
                        }
                    } else {
                        $order[$key] = $val;
                    }
                }
                array_push($orders, $order);
            }

            $visibleColumns = ["number", "priceIncl", "email", "channel", "statusTitle"];
            $omittedColumns = ["status", "customStatusId", "firstname", "middlename", "lastname"];
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

            $columnsConfig .= ', { caption: "Customer Name", calculateCellValue: function(data) { return [data.firstname, data.middlename, data.lastname].join(" "); }}';

            View::share("orders", $orders);
            View::share("columns", $columnsConfig);
        }
    }
}