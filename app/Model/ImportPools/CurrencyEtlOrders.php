<?php

namespace App\Model\ImportPools;

/**
 * Class CurrencyEtlOrders
 * @package App\Model\ImportPools
 * @author Tomas
 * @property integer $id
 * @property integer $order_id
 * @property integer $project_id
 * @property integer $currency_etl_catalog_id
 * @property integer $active
 * @mixin \Eloquent
 */
class CurrencyEtlOrders extends Model {
    public $timestamps = false;

    protected $table = 'currency_etl_orders';
}
