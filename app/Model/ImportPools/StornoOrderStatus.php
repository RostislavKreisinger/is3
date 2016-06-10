<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\ImportPools;

use App\Model\ImportPools\Model;
use Illuminate\Database\Eloquent\Builder;



class StornoOrderStatus extends Model {
    
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    
    public $timestamps = false;

    protected $table = 'storno_order_status';
    
    public static function getAllUnsolvedStornoOrderStatuses (){
        return self::where('active', '=', 0);
    }
    
    
    
    
}
