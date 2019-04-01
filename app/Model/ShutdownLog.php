<?php

namespace App\Model;


use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class ShutdownLog
 *
 * @package App\Model
 * @property int $id
 * @property string $datetime
 * @property int $project_id
 * @property int $resource_id
 * @property string|null $message
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ShutdownLog onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ShutdownLog whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ShutdownLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ShutdownLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ShutdownLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ShutdownLog whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ShutdownLog whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ShutdownLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ShutdownLog withoutTrashed()
 * @mixin \Eloquent
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