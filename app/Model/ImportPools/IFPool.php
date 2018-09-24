<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDImportFlowConnections;

/**
 * Class IFPool
 * @package App\Model\ImportPools
 */
abstract class IFPool extends Model {
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