<?php

namespace App\Model;

use Eloquent;

class Timezone extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'timezone';
    
    protected $guarded = [];
    
}
