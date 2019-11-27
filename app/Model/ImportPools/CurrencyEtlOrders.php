<?php

namespace App\Model\ImportPools;


use Eloquent;

/**
 * Class CurrencyEtlOrders
 *
 * @package App\Model\ImportPools
 * @author Tomas
 * @property integer $id
 * @property integer $order_id
 * @property integer $project_id
 * @property integer $currency_etl_catalog_id
 * @property integer $active
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlOrders whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlOrders whereCurrencyEtlCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlOrders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlOrders whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlOrders whereProjectId($value)
 */
class CurrencyEtlOrders extends PoolModel {
    public $timestamps = false;

    protected $table = 'currency_etl_orders';
}
