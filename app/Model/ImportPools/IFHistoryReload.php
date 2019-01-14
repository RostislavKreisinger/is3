<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Monkey\Connections\MDImportFlowConnections;

/**
 * Class IFHistoryReload
 *
 * @package App\Model\ImportPools
 * @property-read Project $project
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $project_id
 * @property string|null $unique
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryReload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryReload whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryReload whereUnique($value)
 */
class IFHistoryReload extends Model {
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'if_history_reload';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the database connection for the model.
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection() {
        return MDImportFlowConnections::getImportFlowConnection();
    }
}