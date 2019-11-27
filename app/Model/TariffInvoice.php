<?php

namespace App\Model;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Model\TariffInvoice
 *
 * @property int $id
 * @property int $client_id
 * @property string|null $phone1
 * @property string|null $phone2
 * @property string|null $mail
 * @property string|null $city
 * @property string|null $street
 * @property string|null $postcode
 * @property int|null $country_id
 * @property int|null $currency_id
 * @property string|null $company_name
 * @property bool $is_company
 * @property string|null $ic
 * @property string|null $dic
 * @property string|null $dan_cislo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|TariffInvoice whereCity($value)
 * @method static Builder|TariffInvoice whereClientId($value)
 * @method static Builder|TariffInvoice whereCompanyName($value)
 * @method static Builder|TariffInvoice whereCountryId($value)
 * @method static Builder|TariffInvoice whereCreatedAt($value)
 * @method static Builder|TariffInvoice whereCurrencyId($value)
 * @method static Builder|TariffInvoice whereDanCislo($value)
 * @method static Builder|TariffInvoice whereDeletedAt($value)
 * @method static Builder|TariffInvoice whereDic($value)
 * @method static Builder|TariffInvoice whereIc($value)
 * @method static Builder|TariffInvoice whereId($value)
 * @method static Builder|TariffInvoice whereIsCompany($value)
 * @method static Builder|TariffInvoice whereMail($value)
 * @method static Builder|TariffInvoice wherePhone1($value)
 * @method static Builder|TariffInvoice wherePhone2($value)
 * @method static Builder|TariffInvoice wherePostcode($value)
 * @method static Builder|TariffInvoice whereStreet($value)
 * @method static Builder|TariffInvoice whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TariffInvoice onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TariffInvoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\TariffInvoice withoutTrashed()
 */
class TariffInvoice extends MasterModel {
    use SoftDeletes;

    protected $table = 'tariff_invoice';
    protected $guarded = [];
}
