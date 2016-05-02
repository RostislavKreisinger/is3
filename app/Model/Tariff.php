<?php

namespace App\Model;

use Eloquent;

class Tariff extends Eloquent {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'tariff';
    
    protected $guarded = [];
 
}
