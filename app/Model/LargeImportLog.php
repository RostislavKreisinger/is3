<?php

namespace App\Model;


use App\Model\ImportPools\IFControlPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDImportFlowConnections;

/**
 * Class LargeImportLog
 * @package App\Model
 */
class LargeImportLog extends Model {
    /**
     * @var string $table
     */
    protected $table = 'large_import_log';

    use SoftDeletes;

    /**
     * @return BelongsTo
     */
    public function controlPool(): BelongsTo {
        return $this->belongsTo(IFControlPool::class, 'if_control_id');
    }

    /**
     * @return LaravelMySqlConnection
     */
    public function getConnection(): LaravelMySqlConnection {
        return MDImportFlowConnections::getImportFlowConnection();
    }
}