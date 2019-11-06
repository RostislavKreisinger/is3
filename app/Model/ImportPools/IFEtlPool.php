<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IFEtlPool
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
 * @method static Builder|IFEtlPool whereActive($value)
 * @method static Builder|IFStepPool whereActiveIn($active)
 * @method static Builder|IFEtlPool whereCreatedAt($value)
 * @method static Builder|IFEtlPool whereDelayCount($value)
 * @method static Builder|IFEtlPool whereDeletedAt($value)
 * @method static Builder|IFEtlPool whereFinishAt($value)
 * @method static Builder|IFEtlPool whereHostname($value)
 * @method static Builder|IFEtlPool whereId($value)
 * @method static Builder|IFStepPool whereOlderThan($datetime)
 * @method static Builder|IFEtlPool wherePid($value)
 * @method static Builder|IFEtlPool whereProjectId($value)
 * @method static Builder|IFEtlPool whereResourceId($value)
 * @method static Builder|IFEtlPool whereStartAt($value)
 * @method static Builder|IFEtlPool whereTtl($value)
 * @method static Builder|IFEtlPool whereUnique($value)
 * @method static Builder|IFEtlPool whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|IFStepPool notDelayed()
 */
class IFEtlPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_etl';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'ETL';
}
