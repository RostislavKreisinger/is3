<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Model\EshopType|null $eshopTypeName
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\ResourceSetting[] $resourceSettings
 * @property-read \App\Model\User|null $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Project onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereBusinessAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereEshopTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereModuleMasksId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereModuleMasksIdApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereModuleMasksIdInside($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereModuleMasksIdOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereProjectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereProjectTypeInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereTestProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Project whereWeburl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\Project withoutTrashed()
 * @mixin \Eloquent
 */
class Project extends Model {

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'project';
    
    protected $guarded = [];
    
    public function getResources() {
        return $this->belongsToMany(Resource::class, "resource_setting", "project_id", "resource_id");
    }

    /**
     * @param int|null $resourceId
     * @return HasMany
     */
    public function resourceSettings(int $resourceId = null): HasMany {
        $builder = $this->hasMany(ResourceSetting::class);

        if (!is_null($resourceId)) {
            $builder->where('resource_id', '=', $resourceId);
        }

        return $builder;
    }

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user()->first();
    }

    /**
     * @return Collection
     */
    public function getAutoReports() {
        return $this->hasMany(AutoReportPool::class)->get();
    }

    /**
     * @return BelongsTo
     */
    public function eshopTypeName(): BelongsTo {
        return $this->belongsTo(EshopType::class, 'eshop_type_id')->select(['id', 'name']);
    }
}
