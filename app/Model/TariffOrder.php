<?php

namespace App\Model;

use Eloquent;

class TariffOrder extends Eloquent {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'tariff_order';
    
    protected $guarded = [];
    
   

}
