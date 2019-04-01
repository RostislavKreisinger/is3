<?php

namespace App\Model;

use Eloquent;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereDanCislo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereDic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereIc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereIsCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereMail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice wherePhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice wherePhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\TariffInvoice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TariffInvoice extends Eloquent {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'tariff_invoice';
    
    protected $guarded = [];
    
   

}
