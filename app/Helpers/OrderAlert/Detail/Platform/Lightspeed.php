<?php

namespace App\Helpers\OrderAlert\Detail\Platform;


use App\Helpers\OrderAlert\Detail\ABasePlatform;
use Monkey\Connections\MDOrderAlertConnections;

class Lightspeed extends ABasePlatform {



    public function getVisibleColumns(): array {
        // TODO: Implement getVisibleColumns() method.
    }

    /**
     * @return array
     */
    public function getOmittedColumns(): array {
        // TODO: Implement getOmittedColumns() method.
    }

    public function getColumnsConfig(): string {
        // TODO: Implement getColumnsConfig() method.
    }

    public function getRawOrders(int $storeId): array {
        $ordersRaw = MDOrderAlertConnections::getOrderAlertDwConnection()->table("f_order_eshop_" . $storeId)
            ->leftJoin('d_ls_order_status', 'f_order_eshop_' . $storeId . '.status_id', '=', 'd_ls_order_status.id')
            ->get(['f_order_eshop_' . $storeId . '.*', 'd_ls_order_status.status', 'd_ls_order_status.title']);
    }
}