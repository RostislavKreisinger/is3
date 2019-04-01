<?php

namespace App\Model\ImportPools;

use App\Model\Model as BaseModel;


/**
 * App\Model\ImportPools\Model
 *
 * @mixin \Eloquent
 */
class Model extends BaseModel{

    protected $connection = 'mysql-import-pools'; 
}
