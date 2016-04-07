<?php

namespace App\Model;

use Eloquent;

class Project extends Eloquent {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'project';
    
    protected $guarded = [];

}
