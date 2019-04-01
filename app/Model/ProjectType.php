<?php

namespace App\Model;


use Illuminate\Database\Eloquent\SoftDeletes;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class ProjectType
 *
 * @package App\Model
 * @property int $id
 * @property string|null $btf_name
 * @property bool|null $type
 * @property int $module_id
 * @property bool $active
 * @property string|null $icon_class
 * @property string $icon_class2
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ProjectType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereBtfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereIconClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereIconClass2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ProjectType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ProjectType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\ProjectType withoutTrashed()
 * @mixin \Eloquent
 */
class ProjectType extends Model {
    use SoftDeletes;

    /**
     * @inheritdoc
     */
    protected $table = 'project_type';

    /**
     * @inheritdoc
     */
    public function getConnection() {
        return MDDatabaseConnections::getMasterAppConnection();
    }
}