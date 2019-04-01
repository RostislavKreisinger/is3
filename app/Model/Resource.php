<?php

namespace App\Model;

use App\Model\ImportSupport\ResourceError;
use DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Model\Resource
 *
 * @property int $id
 * @property int|null $source_type_id
 * @property int|null $resource_type_id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $btf_name
 * @property bool $order
 * @property string|null $tbl
 * @property string|null $db_import
 * @property string|null $color
 * @property string|null $tbl_setting
 * @property string|null $url
 * @property bool|null $allow_link
 * @property bool $active
 * @property string|null $img
 * @property string|null $import_class
 * @property bool|null $default_date
 * @property bool $setting_tutorial
 * @property int|null $import_interval
 * @property int|null $import_history_interval
 * @property bool $import_version
 * @property string|null $logo
 * @property int|null $logo_big_width
 * @property int|null $logo_small_width
 * @property bool $is_public
 * @property string|null $codename
 * @property bool|null $use_title
 * @property bool|null $use_footnote
 * @property string|null $custom_form_btfs
 * @property string|null $youtube_link
 * @property bool|null $is_onlinestore resource with online store properties
 * @property bool|null $is_resource is standard resource
 * @property string $data_storage_connection
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\ImportSupport\ResourceError[] $builderResourceErrors
 * @property-read \Illuminate\Database\Eloquent\Collection|ResourceSetting[] $resourceSettings
 * @method static Builder|Resource whereActive($value)
 * @method static Builder|Resource whereAllowLink($value)
 * @method static Builder|Resource whereBtfName($value)
 * @method static Builder|Resource whereCode($value)
 * @method static Builder|Resource whereCodename($value)
 * @method static Builder|Resource whereColor($value)
 * @method static Builder|Resource whereCustomFormBtfs($value)
 * @method static Builder|Resource whereDataStorageConnection($value)
 * @method static Builder|Resource whereDbImport($value)
 * @method static Builder|Resource whereDefaultDate($value)
 * @method static Builder|Resource whereId($value)
 * @method static Builder|Resource whereImg($value)
 * @method static Builder|Resource whereImportClass($value)
 * @method static Builder|Resource whereImportHistoryInterval($value)
 * @method static Builder|Resource whereImportInterval($value)
 * @method static Builder|Resource whereImportVersion($value)
 * @method static Builder|Resource whereIsOnlinestore($value)
 * @method static Builder|Resource whereIsPublic($value)
 * @method static Builder|Resource whereIsResource($value)
 * @method static Builder|Resource whereLogo($value)
 * @method static Builder|Resource whereLogoBigWidth($value)
 * @method static Builder|Resource whereLogoSmallWidth($value)
 * @method static Builder|Resource whereName($value)
 * @method static Builder|Resource whereOrder($value)
 * @method static Builder|Resource whereResourceTypeId($value)
 * @method static Builder|Resource whereSettingTutorial($value)
 * @method static Builder|Resource whereSourceTypeId($value)
 * @method static Builder|Resource whereTbl($value)
 * @method static Builder|Resource whereTblSetting($value)
 * @method static Builder|Resource whereUrl($value)
 * @method static Builder|Resource whereUseFootnote($value)
 * @method static Builder|Resource whereUseTitle($value)
 * @method static Builder|Resource whereYoutubeLink($value)
 * @mixin \Eloquent
 */
class Resource extends Model {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'resource';
    protected $guarded = [];

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('id', function(Builder $builder) {
//            $builder->whereNotIn('resource.id', [1, 19]);
        });
    }

    public function getResourceErrors($projectId = null) {
        $errors = $this->builderResourceErrors()->get();
        if ($projectId !== null) {
            foreach ($errors as &$error) {
                $key = "project_{$projectId}_error_{$error->id}";
                $result = DB::connection('mysql-app-support')
                        ->table('crm_tickets')
                        ->where('unique_action', '=', $key)
                        ->first();
                $error->sent = (bool) $result;
            }
        }
        return $errors;
    }

    public function builderResourceErrors() {
        return $this->hasMany(ResourceError::class, 'resource_id');
    }

    public function resourceSettings() {
        return $this->hasMany(ResourceSetting::class);
    }
}
