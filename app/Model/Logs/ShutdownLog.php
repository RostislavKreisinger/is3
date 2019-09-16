<?php

namespace App\Model\Logs;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ShutdownLog
 * @package App\Model\Logs
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
