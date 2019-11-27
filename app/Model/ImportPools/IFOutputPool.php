<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IFOutputPool
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
 * @method static Builder|IFOutputPool whereActive($value)
 * @method static Builder|IFStepPool whereActiveIn($active)
 * @method static Builder|IFOutputPool whereCreatedAt($value)
 * @method static Builder|IFOutputPool whereDelayCount($value)
 * @method static Builder|IFOutputPool whereDeletedAt($value)
 * @method static Builder|IFOutputPool whereFinishAt($value)
 * @method static Builder|IFOutputPool whereHostname($value)
 * @method static Builder|IFOutputPool whereId($value)
 * @method static Builder|IFStepPool whereOlderThan($datetime)
 * @method static Builder|IFOutputPool wherePid($value)
 * @method static Builder|IFOutputPool whereProjectId($value)
 * @method static Builder|IFOutputPool whereResourceId($value)
 * @method static Builder|IFOutputPool whereStartAt($value)
 * @method static Builder|IFOutputPool whereTtl($value)
 * @method static Builder|IFOutputPool whereUnique($value)
 * @method static Builder|IFOutputPool whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|IFStepPool notDelayed()
 */
class IFOutputPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_output';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'Output';
}
