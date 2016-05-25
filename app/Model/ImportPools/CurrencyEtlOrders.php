<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportPools;

use App\Model\ImportPools\Model;



/**
 * Description of Acl
 *
 * @author Tomas
 * @property integer $id
 * @property string $key
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Acl whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Acl whereKey($value)
 * @mixin \Eloquent
 */
class CurrencyEtlOrders extends Model {
    
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    
    public $timestamps = false;

    protected $table = 'currency_etl_orders';
    
    
    
    
}
