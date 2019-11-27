<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
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
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function resource(): BelongsTo {
        return $this->belongsTo(Resource::class);
    }

    /**
     * @return LaravelMySqlConnection
     */
    public function getConnection(): LaravelMySqlConnection {
        return MDImportFlowConnections::getImportFlowConnection();
    }
}
