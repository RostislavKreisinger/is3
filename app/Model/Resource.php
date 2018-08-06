<?php

namespace App\Model;

use App\Model\ImportSupport\ResourceError;
use DB;
use Illuminate\Database\Eloquent\Builder;

class Resource extends Model {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'resource';
    protected $guarded = [];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('id', function(Builder $builder) {
            $builder->whereNotIn('resource.id', [1, 19]);
        });
    }

    public function getResourceErrors($projectId = null) {
        $errors = $this->builderResourceErrors()->get();
        if ($projectId !== null) {
            foreach ($errors as &$error) {
                $key = "project_{$projectId}_error_{$error->id}";
                $result = DB::connection('mysql-app-support')
                        ->table('crm_tickets')
                        ->where('unique_action', '=', $key)
                        ->first();
                $error->sent = (bool) $result;
            }
        }
        return $errors;
    }

    public function builderResourceErrors() {
        return $this->hasMany(ResourceError::class, 'resource_id');
    }

    public function resourceSettings() {
        return $this->hasMany(ResourceSetting::class);
    }
}
