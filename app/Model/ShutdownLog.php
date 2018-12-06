<?php

namespace App\Model;


use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class ShutdownLog
 * @package App\Model
 */
class ShutdownLog extends Model {
    use SoftDeletes;

    /**
     * @var string $table
     */
    protected $table = 'if_shutdown_log';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @return Connection|LaravelMySqlConnection
     */
    public function getConnection() {
        return MDDatabaseConnections::getLogsConnection();
    }
}