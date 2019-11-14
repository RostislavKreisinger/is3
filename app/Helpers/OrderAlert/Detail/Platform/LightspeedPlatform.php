<?php

namespace App\Helpers\OrderAlert\Detail\Platform;

use App\Helpers\OrderAlert\Detail\ABasePlatform;
use Illuminate\Support\Collection;
use Monkey\Connections\MDOrderAlertConnections;

class LightspeedPlatform extends ABasePlatform {

    /**
     * @return array
     */
    public function getVisibleColumns(): array {
        return ["number", "priceIncl", "email", "channel", "statusTitle"];
    }

    /**
     * @return array
     */
    public function getOmittedColumns(): array {
        return ["status", "customStatusId", "firstname", "middlename", "lastname"];
    }

    /**
     * @return string
     */
    public function getColumnsConfig(): string {
        return ', { caption: "Customer Name", calculateCellValue: function(data) { return [data.firstname, data.middlename, data.lastname].join(" "); }}';
    }

    /**
     * @param int $storeId
     * @return Collection
     */
    public function getRawOrders(int $storeId): Collection {
        return MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
//            ->leftJoin('d_ls_order_status', 'f_order_eshop_' . $storeId . '.status_id', '=', 'd_ls_order_status.id')
            ->get(['f_order_eshop_' . $storeId . '.*'/*, 'd_ls_order_status.status', 'd_ls_order_status.title'*/]);
    }

    /**
     * @param string $orderStatus
     * @return null|string
     */
    public function translateOrderStatus(string $orderStatus) {
        return \Tr::translate($orderStatus);
    }
}
