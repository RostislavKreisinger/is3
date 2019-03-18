<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ResourceSetting
 *
 * @package App\Model
 * @property int $id
 * @property int|null $project_id
 * @property int|null $currency_id
 * @property string $resource_code
 * @property int|null $resource_id
 * @property bool|null $active 100 - fake records for POS included to eshop
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool $ttl
 * @property string|null $next_check_date
 * @property int|null $customer_currency_id
 * @property int|null $custom_import_history_interval
 * @property bool $workload_difficulty
 * @property string|null $note
 * @property-read \App\Model\Project|null $project
 * @property-read \App\Model\Resource|null $resourceName
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ResourceSetting onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereCustomImportHistoryInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereCustomerCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereNextCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereResourceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSetting whereWorkloadDifficulty($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ResourceSetting withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ResourceSetting withoutTrashed()
 * @mixin \Eloquent
 */
class ResourceSetting extends Model {
    protected $connection = 'mysql-master-app';
    protected $table = 'resource_setting';
    
    protected $guarded = [];

    use SoftDeletes;

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function resourceName(): BelongsTo {
        return $this->belongsTo(Resource::class, 'resource_id')
            ->select(['id', 'name']);
    }

    /**
     * @param Builder $query
     * @param int $active
     * @return Builder
     */
    public function scopeWhereActive(Builder $query, int $active): Builder {
        return $query->where('active', '=', $active);
    }

    /**
     * @return ResourceSetting
     */
    public function activate(): ResourceSetting {
        $this->setAttribute('active', 1);
        $this->setAttribute('ttl', 6);
        return $this;
    }

    /**
     * @return ResourceSetting
     */
    public function deactivate(): ResourceSetting {
        $this->setAttribute('active', 10);
        $this->setAttribute('ttl', 0);
        return $this;
    }

    /**
     * @return ResourceSetting
     */
    public function test(): ResourceSetting {
        $this->setAttribute('active', 0);
        $this->setAttribute('ttl', 5);
        return $this;
    }
}