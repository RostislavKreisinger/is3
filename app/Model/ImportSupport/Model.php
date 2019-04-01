<?php

namespace App\Model\ImportSupport;

use App\Model\Model as BaseModel;


/**
 * App\Model\ImportSupport\Model
 *
 * @mixin \Eloquent
 */
class Model extends BaseModel{

    protected $connection = 'mysql-import-support'; 
}
