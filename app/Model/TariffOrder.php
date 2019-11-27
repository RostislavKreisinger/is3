<?php

namespace App\Model;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $paid_date
 * @property string|null $supply_date
 * @method static Builder|TariffOrder whereBtTransactionKey($value)
 * @method static Builder|TariffOrder whereCount($value)
 * @method static Builder|TariffOrder whereCreatedAt($value)
 * @method static Builder|TariffOrder whereDeletedAt($value)
 * @method static Builder|TariffOrder whereId($value)
 * @method static Builder|TariffOrder wherePaidDate($value)
 * @method static Builder|TariffOrder wherePrice($value)
 * @method static Builder|TariffOrder wherePriceWithVat($value)
 * @method static Builder|TariffOrder whereStav($value)
 * @method static Builder|TariffOrder whereSupplyDate($value)
 * @method static Builder|TariffOrder whereTariffId($value)
 * @method static Builder|TariffOrder whereTariffInvoiceId($value)
 * @method static Builder|TariffOrder whereTariffPaymentMethodId($value)
 * @method static Builder|TariffOrder whereUpdatedAt($value)
 * @method static Builder|TariffOrder whereVat($value)
 * @mixin Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TariffOrder onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TariffOrder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TariffOrder withoutTrashed()
 */
class TariffOrder extends MasterModel {
    use SoftDeletes;

    protected $table = 'tariff_order';
    protected $guarded = [];
}
