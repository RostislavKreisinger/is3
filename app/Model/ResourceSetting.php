<?php

namespace App\Model;


use App\Model\ImportPools\IFDailyPool;
use App\Model\ImportPools\IFHistoryPool;
use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Monkey\Constants\ImportFlow\Resource\ResourceSetting as ResourceSettingConstants;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool $ttl
 * @property string|null $next_check_date
 * @property int|null $customer_currency_id
 * @property int|null $custom_import_history_interval
 * @property bool $workload_difficulty
 * @property string|null $note
 * @property-read Project|null $project
 * @property-read Resource|null $resourceName
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|ResourceSetting onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|ResourceSetting whereActive($value)
 * @method static Builder|ResourceSetting whereCreatedAt($value)
 * @method static Builder|ResourceSetting whereCurrencyId($value)
 * @method static Builder|ResourceSetting whereCustomImportHistoryInterval($value)
 * @method static Builder|ResourceSetting whereCustomerCurrencyId($value)
 * @method static Builder|ResourceSetting whereDeletedAt($value)
 * @method static Builder|ResourceSetting whereId($value)
 * @method static Builder|ResourceSetting whereNextCheckDate($value)
 * @method static Builder|ResourceSetting whereNote($value)
 * @method static Builder|ResourceSetting whereProjectId($value)
 * @method static Builder|ResourceSetting whereResourceCode($value)
 * @method static Builder|ResourceSetting whereResourceId($value)
 * @method static Builder|ResourceSetting whereTtl($value)
 * @method static Builder|ResourceSetting whereUpdatedAt($value)
 * @method static Builder|ResourceSetting whereWorkloadDifficulty($value)
 * @method static QueryBuilder|ResourceSetting withTrashed()
 * @method static QueryBuilder|ResourceSetting withoutTrashed()
 * @mixin Eloquent
 * @property-read Resource|null $resource
 * @property-read Currency|null $currency
 */
class ResourceSetting extends MasterModel {
    use Compoships, SoftDeletes;

    protected $table = 'resource_setting';
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();
        static::addGlobalScope('resource_id', function (Builder $builder) {
            $builder->whereNotIn('resource_id', [1, 19]);
        });
    }

    /**
     * @return QueryBuilder
     */
    public function connectionData() {
        return $this->getConnection()->table($this->resource->tbl_setting)->where('resource_setting_id', $this->id);
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo
     */
    public function resource(): BelongsTo {
        return $this->belongsTo(Resource::class);
    }

    /**
     * @return HasOne
     */
    public function dailyPool() {
        return $this->hasOne(IFDailyPool::class, ['project_id', 'resource_id'], ['project_id', 'resource_id']);
    }

    /**
     * @return HasOne
     */
    public function historyPool() {
        return $this->hasOne(IFHistoryPool::class, ['project_id', 'resource_id'], ['project_id', 'resource_id']);
    }

    /**
     * @return ResourceSetting
     */
    public function activate(): ResourceSetting {
        $this->setAttribute('active', ResourceSettingConstants::ACTIVE);
        $this->setAttribute('ttl', ResourceSettingConstants::TTL_TESTED);
        return $this;
    }

    /**
     * @return ResourceSetting
     */
    public function deactivate(): ResourceSetting {
        $this->setAttribute('active', ResourceSettingConstants::MANUAL);
        $this->setAttribute('ttl', ResourceSettingConstants::TTL_EXHAUSTED);
        return $this;
    }

    /**
     * @return ResourceSetting
     */
    public function test(): ResourceSetting {
        $this->setAttribute('active', ResourceSettingConstants::TESTING);
        $this->setAttribute('ttl', ResourceSettingConstants::TTL_DEFAULT);
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool {
        return $this->active !== ResourceSettingConstants::ERROR && $this->ttl > ResourceSettingConstants::TTL_EXHAUSTED;
    }
}
