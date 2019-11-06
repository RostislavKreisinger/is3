<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IFCalcPool
 *
 * @package App\Model\ImportPools
 * @property int $id
 * @property bool $active
 * @property bool $ttl
 * @property int $project_id
 * @property int $resource_id
 * @property string $unique
 * @property string|null $start_at
 * @property string|null $finish_at
 * @property int|null $delay_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $hostname
 * @property int|null $pid
 * @property string|null $deleted_at
 * @property-read IFControlPool $controlPool
 * @property-read string $if_step
 * @property-read Project $project
 * @property-read Resource $resource
 * @method static Builder|IFStepPool delayed()
 * @method static Builder|IFCalcPool whereActive($value)
 * @method static Builder|IFStepPool whereActiveIn($active)
 * @method static Builder|IFCalcPool whereCreatedAt($value)
 * @method static Builder|IFCalcPool whereDelayCount($value)
 * @method static Builder|IFCalcPool whereDeletedAt($value)
 * @method static Builder|IFCalcPool whereFinishAt($value)
 * @method static Builder|IFCalcPool whereHostname($value)
 * @method static Builder|IFCalcPool whereId($value)
 * @method static Builder|IFStepPool whereOlderThan($datetime)
 * @method static Builder|IFCalcPool wherePid($value)
 * @method static Builder|IFCalcPool whereProjectId($value)
 * @method static Builder|IFCalcPool whereResourceId($value)
 * @method static Builder|IFCalcPool whereStartAt($value)
 * @method static Builder|IFCalcPool whereTtl($value)
 * @method static Builder|IFCalcPool whereUnique($value)
 * @method static Builder|IFCalcPool whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|IFStepPool notDelayed()
 */
class IFCalcPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_calc';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'Calc';
}
