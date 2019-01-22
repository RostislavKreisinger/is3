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
use Monkey\Translator;

class DetailController extends BaseController {
    public function getIndex($storeId) {
        $eshop = MDOrderAlertConnections::getOrderAlertConnection()->table('eshop')->where('eshop.id', '=', "$storeId")
            ->leftJoin('currency', 'eshop.currency_id', '=', 'currency.id')
            ->first(['eshop.*','currency.code']);

        if (!is_null($eshop)) {
            $eshop->type = EshopType::getById($eshop->eshop_type_id);

            if ($eshop->eshop_id == '') {
                $eshop->eshop_id = '??';
            }

            foreach($eshop as $key=>$value) {
                if ($value == '') {
                    $eshop->$key = '--';
                }
            }

            View::share('eshop', $eshop);

            $eshopType = '';
            $shouldJoinOrderStatusTable = false;

            if ($eshop->eshop_type_id == EshopType::CODE_LIGHTSPEED) {
                $eshopType = 'ls';
                $shouldJoinOrderStatusTable = true;
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_WOOCOMMERCE) {
                $eshopType = 'wc';
                $shouldJoinOrderStatusTable = true;
            }

            if ($shouldJoinOrderStatusTable) {
                $ordersRaw = MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
                    ->leftJoin('d_' . $eshopType . '_order_status', 'f_order_eshop_' . $storeId . '.status_id', '=', 'd_' . $eshopType . '_order_status.id')
                    ->get(['f_order_eshop_' . $storeId . '.*', 'd_' . $eshopType . '_order_status.status', 'd_' . $eshopType . '_order_status.title']);
            }
            else {
                $ordersRaw = MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
                    ->get(['f_order_eshop_' . $storeId . '.*']);
            }

            $orders = array();
            foreach ($ordersRaw as $orderRaw) {
                $orderJSON = json_decode($orderRaw->json, true);
                $order = array();

                if ($shouldJoinOrderStatusTable) {
                    $order['statusTitle'] = \Tr::translate($orderRaw->title);
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

            $visibleColumns = [];
            $omittedColumns = [];

            if ($eshop->eshop_type_id == EshopType::CODE_LIGHTSPEED) {
                $visibleColumns = ["number", "priceIncl", "email", "channel", "statusTitle"];
                $omittedColumns = ["status", "customStatusId", "firstname", "middlename", "lastname"];
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_WOOCOMMERCE) {
                $visibleColumns = ["number", "email", "channel", "statusTitle", "total","payment_method_title"];
                $omittedColumns = ["status", "customStatusId", "first_name", "last_name"];
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_SHOPTET) {
                $visibleColumns = ["code", "orderCode", "toPay", "currencyCode", "email", "creationTime"];
                $omittedColumns = ["status", "customStatusId", "first_name", "last_name", "statusTitle"];
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_EPAGES) {
                $visibleColumns = ["orderId", "orderNumber", "creationDate", "customerNumber", "currencyId", "grandTotal"];
                $omittedColumns = ["status", "customStatusId", "first_name", "last_name", "statusTitle"];
            }

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

            if ($eshop->eshop_type_id == EshopType::CODE_LIGHTSPEED) {
                $columnsConfig .= ', { caption: "Customer Name", calculateCellValue: function(data) { return [data.firstname, data.middlename, data.lastname].join(" "); }}';
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_WOOCOMMERCE) {
                $columnsConfig .= ', { caption: "Customer Name", calculateCellValue: function(data) { return [data.first_name, data.last_name].join(" "); }}';
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_SHOPTET) {
                $columnsConfig .= ', { caption: "Status", calculateCellValue: function(data) { return data.name; }}';
            }
            else if ($eshop->eshop_type_id == EshopType::CODE_EPAGES) {
                $columnsConfig .= ', { caption: "Customer Name", calculateCellValue: function(data) { return [data.firstName, data.middleName, data.lastName].join(" "); }}';
            }

            View::share('orders', $orders);
            View::share('columns', $columnsConfig);
        }
    }
}