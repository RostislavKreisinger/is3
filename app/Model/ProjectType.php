<?php

namespace App\Model;


use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * App\Model\ProjectType
 *
 * @property int $id
 * @property string|null $btf_name
 * @property bool|null $type
 * @property int $module_id
 * @property bool $active
 * @property string|null $icon_class
 * @property string $icon_class2
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static QueryBuilder|ProjectType onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|ProjectType whereActive($value)
 * @method static Builder|ProjectType whereBtfName($value)
 * @method static Builder|ProjectType whereCreatedAt($value)
 * @method static Builder|ProjectType whereDeletedAt($value)
 * @method static Builder|ProjectType whereIconClass($value)
 * @method static Builder|ProjectType whereIconClass2($value)
 * @method static Builder|ProjectType whereId($value)
 * @method static Builder|ProjectType whereModuleId($value)
 * @method static Builder|ProjectType whereType($value)
 * @method static Builder|ProjectType whereUpdatedAt($value)
 * @method static QueryBuilder|ProjectType withTrashed()
 * @method static QueryBuilder|ProjectType withoutTrashed()
 * @mixin Eloquent
 */
class ProjectType extends MasterModel {
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_type';
}
