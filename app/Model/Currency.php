<?php

namespace App\Model;

/**
 * Class Currency
 * @package App\Model
 */
class Currency extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'currency_names';
    
    protected $guarded = [];
    
}