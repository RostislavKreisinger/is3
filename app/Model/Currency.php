<?php

namespace App\Model;

use Eloquent;

class Currency extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'currency_names';
    
    protected $guarded = [];
    
}
