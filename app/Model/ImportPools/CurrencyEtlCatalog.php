<?php

namespace App\Model\ImportPools;


/**
 * Class CurrencyEtlCatalog
 *
 * @package App\Model\ImportPools
 * @property int $id
 * @property string|null $ISO3
 * @property int|null $currency_names_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlCatalog whereCurrencyNamesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlCatalog whereISO3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ImportPools\CurrencyEtlCatalog whereId($value)
 * @mixin \Eloquent
 */
class CurrencyEtlCatalog extends PoolModel {
    public $timestamps = false;

    protected $table = 'currency_etl_catalog';
    
    public function getCurrencyEtlOrders() {
        return $this->hasMany(CurrencyEtlOrders::class);
    }
    
    public static function getToResolve() {
        return self::whereNull('currency_names_id')->get();
    }
}
