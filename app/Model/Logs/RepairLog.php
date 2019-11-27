<?php

namespace App\Model\Logs;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RepairLog
 *
 * @package App\Model\Logs
 * @property int $id
 * @property int $project_id
 * @property int $resource_id
 * @property string $unique
 * @property string $step
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Logs\RepairLog onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Logs\RepairLog whereUnique($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Logs\RepairLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Logs\RepairLog withoutTrashed()
 * @mixin \Eloquent
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
