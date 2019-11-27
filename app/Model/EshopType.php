<?php

namespace App\Model;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Model\EshopType
 *
 * @property int $id
 * @property string $code
 * @property bool|null $dynamic_route
 * @property string $name
 * @property bool|null $active
 * @property bool $public
 * @property bool $order
 * @property bool $pos_included
 * @property string|null $img
 * @property string|null $icon_class
 * @property bool|null $http_authentication
 * @property bool $setting_tutorial
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $logo
 * @property int|null $logo_big_width
 * @property int|null $logo_small_width
 * @property bool|null $use_title
 * @property bool|null $use_footnote
 * @property bool $import_version
 * @property int $import_interval
 * @property int $import_history_interval
 * @property string|null $custom_form_btfs
 * @property string|null $youtube_link
 * @property string|null $data_storage_connection
 * @method static Builder|EshopType whereActive($value)
 * @method static Builder|EshopType whereCode($value)
 * @method static Builder|EshopType whereCreatedAt($value)
 * @method static Builder|EshopType whereCustomFormBtfs($value)
 * @method static Builder|EshopType whereDataStorageConnection($value)
 * @method static Builder|EshopType whereDeletedAt($value)
 * @method static Builder|EshopType whereDynamicRoute($value)
 * @method static Builder|EshopType whereHttpAuthentication($value)
 * @method static Builder|EshopType whereIconClass($value)
 * @method static Builder|EshopType whereId($value)
 * @method static Builder|EshopType whereImg($value)
 * @method static Builder|EshopType whereImportHistoryInterval($value)
 * @method static Builder|EshopType whereImportInterval($value)
 * @method static Builder|EshopType whereImportVersion($value)
 * @method static Builder|EshopType whereLogo($value)
 * @method static Builder|EshopType whereLogoBigWidth($value)
 * @method static Builder|EshopType whereLogoSmallWidth($value)
 * @method static Builder|EshopType whereName($value)
 * @method static Builder|EshopType whereOrder($value)
 * @method static Builder|EshopType wherePosIncluded($value)
 * @method static Builder|EshopType wherePublic($value)
 * @method static Builder|EshopType whereSettingTutorial($value)
 * @method static Builder|EshopType whereUpdatedAt($value)
 * @method static Builder|EshopType whereUseFootnote($value)
 * @method static Builder|EshopType whereUseTitle($value)
 * @method static Builder|EshopType whereYoutubeLink($value)
 * @mixin Eloquent
 */
class EshopType extends MasterModel {
    protected $table = 'eshop_type';
    protected $guarded = [];
}
