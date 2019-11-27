<?php

namespace App\Model;


use App\Model\ImportPools\IFControlPool;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\Extension\LaravelMySqlConnection;
use Monkey\Connections\MDImportFlowConnections;

/**
 * Class LargeImportLog
 *
 * @package App\Model
 * @property int $id
 * @property int $if_control_id
 * @property bool $unit KB=1;MB=2;GB=3;PCS=4
 * @property int $size
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read IFControlPool $controlPool
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|LargeImportLog onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|LargeImportLog whereCreatedAt($value)
 * @method static Builder|LargeImportLog whereDeletedAt($value)
 * @method static Builder|LargeImportLog whereId($value)
 * @method static Builder|LargeImportLog whereIfControlId($value)
 * @method static Builder|LargeImportLog whereSize($value)
 * @method static Builder|LargeImportLog whereUnit($value)
 * @method static Builder|LargeImportLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|LargeImportLog withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LargeImportLog withoutTrashed()
 * @mixin Eloquent
 */
class LargeImportLog extends Model {
    use SoftDeletes;

    const UNITS = [
        1 => 'KB',
        2 => 'MB',
        3 => 'GB',
        4 => 'PCS'
    ];

    /**
     * @var string $table
     */
    protected $table = 'large_import_log';

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
