<?php

namespace App\Model\Logs;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ShutdownLog
 *
 * @package App\Model\Logs
 * @property int $id
 * @property string $datetime
 * @property int $project_id
 * @property int $resource_id
 * @property string|null $message
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Logs\ShutdownLog onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\ShutdownLog whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\ShutdownLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\ShutdownLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\ShutdownLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\ShutdownLog whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\ShutdownLog whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Logs\ShutdownLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Logs\ShutdownLog withoutTrashed()
 * @mixin \Eloquent
 */
class ShutdownLog extends Log {
    use SoftDeletes;

    /**
     * @var string $table
     */
    protected $table = 'if_shutdown_log';

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;
}
