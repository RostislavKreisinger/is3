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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\ResourceSetting[] $resourceSettings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereAllowLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereBtfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereCodename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereCustomFormBtfs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereDataStorageConnection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereDbImport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereDefaultDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereImportClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereImportHistoryInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereImportInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereImportVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereIsOnlinestore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereIsResource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereLogoBigWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereLogoSmallWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereResourceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereSettingTutorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereSourceTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereTbl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereTblSetting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereUseFootnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereUseTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Resource whereYoutubeLink($value)
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
            $builder->whereNotIn('resource.id', [1, 19]);
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
