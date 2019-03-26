<?php

namespace App\Model;


/**
 * Class Timezone
 * @package App\Model
 */
class Timezone extends Model {
    protected $connection = 'mysql-master-app';
    protected $table = 'timezone';
    protected $guarded = [];
}