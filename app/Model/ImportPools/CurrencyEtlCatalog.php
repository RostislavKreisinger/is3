<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportPools;

use App\Model\ImportPools\Model;
use Illuminate\Database\Eloquent\Builder;



class CurrencyEtlCatalog extends Model {
    
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    
    public $timestamps = false;

    protected $table = 'currency_etl_catalog';
    
    public function getCurrencyEtlOrders() {
        return $this->hasMany(CurrencyEtlOrders::class);
    }
    
    public static function getToResolve() {
        return self::whereNull('currency_names_id')->get();
    }
    
    
    
    
}
