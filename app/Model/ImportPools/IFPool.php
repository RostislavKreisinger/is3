<?php

namespace App\Model\ImportPools;


use App\Model\ResourceSetting;
use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDImportFlowConnections;

/**
 * Class IFPool
 * @package App\Model\ImportPools
 */
abstract class IFPool extends Model {
    use SoftDeletes;

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    /**
     * @return BelongsTo
     */
    public function resourceSetting(): BelongsTo {
        return $this->belongsTo(ResourceSetting::class, ['project_id', 'resource_id'], ['project_id', 'resource_id']);
    }

    /**
     * @return LaravelMySqlConnection
     */
    public function getConnection(): LaravelMySqlConnection {
        return MDImportFlowConnections::getImportFlowConnection();
    }
}
