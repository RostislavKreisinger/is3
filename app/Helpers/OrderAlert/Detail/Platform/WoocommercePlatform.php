<?php

namespace App\Helpers\OrderAlert\Detail\Platform;

use App\Helpers\OrderAlert\Detail\ABasePlatform;
use Illuminate\Support\Collection;
use Monkey\Connections\MDOrderAlertConnections;

class WoocommercePlatform extends ABasePlatform {

    /**
     * @return array
     */
    public function getVisibleColumns(): array {
        return ["number", "email", "channel", "statusTitle", "total","payment_method_title"];;
    }

    /**
     * @return array
     */
    public function getOmittedColumns(): array {
        return ["status", "customStatusId", "first_name", "last_name"];
    }

    /**
     * @return string
     */
    public function getColumnsConfig(): string {
        return ', { caption: "Customer Name", calculateCellValue: function(data) { return [data.first_name, data.last_name].join(" "); }}';
    }

    /**
     * @param int $storeId
     * @return Collection
     */
    public function getRawOrders(int $storeId): Collection {
        return MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
            ->leftJoin('d_wc_order_status', 'f_order_eshop_' . $storeId . '.status_id', '=', 'd_wc_order_status.id')
            ->get(['f_order_eshop_' . $storeId . '.*', 'd_wc_order_status.status', 'd_wc_order_status.title']);
    }

    /**
     * @param string $orderStatus
     * @return null|string
     */
    public function translateOrderStatus(string $orderStatus): string {
        return \Tr::translate($orderStatus);
    }
}