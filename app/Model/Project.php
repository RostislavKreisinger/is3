<?php

namespace App\Model;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * App\Model\Project
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $url
 * @property int|null $user_id
 * @property int|null $project_type_id
 * @property string|null $weburl
 * @property string|null $picture
 * @property int|null $country_id
 * @property int|null $currency_id foreign key of table currency_names
 * @property int|null $timezone_id
 * @property int|null $business_area_id
 * @property string|null $project_type_info
 * @property int|null $eshop_type_id
 * @property bool $test_project
 * @property int|null $confirmed
 * @property int|null $module_masks_id
 * @property int|null $module_masks_id_api
 * @property int|null $module_masks_id_inside neni nastaveno pro projekty, ktere nejsou v md_inside
 * @property int|null $module_masks_id_original
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read EshopType|null $eshopType
 * @property-read Collection|ResourceSetting[] $resourceSettings
 * @property-read User|null $user
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|Project onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Project whereBusinessAreaId($value)
 * @method static Builder|Project whereConfirmed($value)
 * @method static Builder|Project whereCountryId($value)
 * @method static Builder|Project whereCreatedAt($value)
 * @method static Builder|Project whereCurrencyId($value)
 * @method static Builder|Project whereDeletedAt($value)
 * @method static Builder|Project whereEshopTypeId($value)
 * @method static Builder|Project whereId($value)
 * @method static Builder|Project whereModuleMasksId($value)
 * @method static Builder|Project whereModuleMasksIdApi($value)
 * @method static Builder|Project whereModuleMasksIdInside($value)
 * @method static Builder|Project whereModuleMasksIdOriginal($value)
 * @method static Builder|Project whereName($value)
 * @method static Builder|Project wherePicture($value)
 * @method static Builder|Project whereProjectTypeId($value)
 * @method static Builder|Project whereProjectTypeInfo($value)
 * @method static Builder|Project whereTestProject($value)
 * @method static Builder|Project whereTimezoneId($value)
 * @method static Builder|Project whereUpdatedAt($value)
 * @method static Builder|Project whereUrl($value)
 * @method static Builder|Project whereUserId($value)
 * @method static Builder|Project whereWeburl($value)
 * @method static QueryBuilder|Project withTrashed()
 * @method static QueryBuilder|Project withoutTrashed()
 * @mixin Eloquent
 * @property-read Currency|null $currency
 * @property-read ProjectType|null $projectType
 */
class Project extends MasterModel {
    use SoftDeletes;

    protected $table = 'project';
    protected $guarded = [];
    
    public function getResources() {
        return $this->belongsToMany(Resource::class, "resource_setting", "project_id", "resource_id");
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
    public function projectType(): BelongsTo {
        return $this->belongsTo(ProjectType::class);
    }

    /**
     * @param int|null $resourceId
     * @return HasMany
     */
    public function resourceSettings(int $resourceId = null): HasMany {
        return $this->hasMany(ResourceSetting::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function eshopType(): BelongsTo {
        return $this->belongsTo(EshopType::class, 'eshop_type_id');
    }
}
