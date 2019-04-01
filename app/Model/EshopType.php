<?php

namespace App\Model;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereCustomFormBtfs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereDataStorageConnection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereDynamicRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereHttpAuthentication($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereImportHistoryInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereImportInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereImportVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereLogoBigWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereLogoSmallWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType wherePosIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereSettingTutorial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereUseFootnote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereUseTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\EshopType whereYoutubeLink($value)
 * @mixin \Eloquent
 */
class EshopType extends Model {

    // use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $connection = 'mysql-master-app';
    protected $table = 'eshop_type';
    
    protected $guarded = [];
    
  
    
    

    
    
    
    

}
