<?php

namespace App\Model;

use Eloquent;

class StackExtend extends Model {


    protected $connection = 'mysql-import-pools';
    protected $table = 'stack_extend';
    
    protected $guarded = [];
    
    public static function findFor($projectId, $resourceId) {
        return self::where('project_id', '=', $projectId)
                ->where('resource_id', '=', $resourceId)
                ->orderBy('created_at')
                ->get();
    }
    
}
