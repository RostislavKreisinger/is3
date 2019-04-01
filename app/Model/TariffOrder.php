<?php

namespace App\Model;

use Eloquent;

/**
 * App\Model\TariffOrder
 *
 * @property int $id
 * @property int|null $tariff_payment_method_id
 * @property int|null $tariff_invoice_id
 * @property int|null $tariff_id
 * @property float|null $price without vat
 * @property float|null $price_with_vat with vat
 * @property float|null $vat
 * @property bool|null $count
 * @property bool|null $stav
 * @property string|null $bt_transaction_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $paid_date
 * @property string|null $supply_date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereBtTransactionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder wherePaidDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder wherePriceWithVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereStav($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereSupplyDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereTariffInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereTariffPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffOrder whereVat($value)
 * @mixin \Eloquent
 */
class TariffOrder extends Eloquent {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'tariff_order';
    
    protected $guarded = [];
    
   

}
