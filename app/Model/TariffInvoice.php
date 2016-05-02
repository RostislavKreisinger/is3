<?php

namespace App\Model;

use Eloquent;

class TariffInvoice extends Eloquent {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'tariff_invoice';
    
    protected $guarded = [];
    
   

}
