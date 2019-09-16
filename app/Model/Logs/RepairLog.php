<?php

namespace App\Model\Logs;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RepairLog
 * @package App\Model\Logs
 */
class RepairLog extends Log {
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'if_repair_log';

    /**
     * Get the name of the "updated at" column.
     *
     * @return null
     */
    public function getUpdatedAtColumn() {
        return null;
    }
}