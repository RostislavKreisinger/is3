<?php

namespace App\Helpers\OrderAlert\Detail\Platform;

use App\Helpers\OrderAlert\Detail\ABasePlatform;
use Illuminate\Support\Collection;
use Monkey\Connections\MDOrderAlertConnections;

class ShoptetPlatform extends ABasePlatform {

    /**
     * @return array
     */
    public function getVisibleColumns(): array {
        return ["code", "orderCode", "toPay", "currencyCode", "email", "creationTime"];
    }

    /**
     * @return array
     */
    public function getOmittedColumns(): array {
        return ["status", "customStatusId", "first_name", "last_name", "statusTitle"];
    }

    /**
     * @return string
     */
    public function getColumnsConfig(): string {
        return ', { caption: "Status", calculateCellValue: function(data) { return data.name; }}';
    }

    /**
     * @param int $storeId
     * @return Collection
     */
    public function getRawOrders(int $storeId): Collection {
        return MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
            ->get(['f_order_eshop_' . $storeId . '.*']);
    }

    /**
     * @param string $orderStatus
     * @return null|string
     */
    public function translateOrderStatus(string $orderStatus) {
        return null;
    }
}