<?php

namespace App\Model;

use Eloquent;

/**
 * App\Model\ResourceSettingV2
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereCustomImportHistoryInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereCustomerCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereNextCheckDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereResourceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ResourceSettingV2 whereWorkloadDifficulty($value)
 * @mixin \Eloquent
 */
class ResourceSettingV2 extends Model {


    protected $connection = 'mysql-master-app';
    protected $table = 'resource_setting';
    
    protected $guarded = [];
    
    

}
