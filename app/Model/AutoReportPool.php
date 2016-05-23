<?php

namespace App\Model;

use Eloquent;

class AutoReportPool extends Model {


    protected $connection = 'mysql-import-pools';
    protected $table = 'auto_report_pool';
    
    protected $guarded = [];
    
    

}
