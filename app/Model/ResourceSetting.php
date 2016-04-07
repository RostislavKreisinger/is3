<?php

namespace App\Model;

use Eloquent;

class ResourceSetting extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'resource_setting';
    
    protected $guarded = [];
    
    

}
