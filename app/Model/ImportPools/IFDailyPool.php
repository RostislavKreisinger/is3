<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IFDailyPool
 *
 * @package App\Model\ImportPools
 * @property int $id
 * @property bool $active
 * @property bool $ttl
 * @property int $project_id
 * @property int $resource_id
 * @property string $next_run_date
 * @property int|null $if_import_id
 * @property string|null $start_at
 * @property string|null $finish_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $unique
 * @property-read Project $project
 * @property-read Resource $resource
 * @method static Builder|IFDailyPool whereActive($value)
 * @method static Builder|IFDailyPool whereCreatedAt($value)
 * @method static Builder|IFDailyPool whereDeletedAt($value)
 * @method static Builder|IFDailyPool whereFinishAt($value)
 * @method static Builder|IFDailyPool whereId($value)
 * @method static Builder|IFDailyPool whereIfImportId($value)
 * @method static Builder|IFDailyPool whereNextRunDate($value)
 * @method static Builder|IFDailyPool whereProjectId($value)
 * @method static Builder|IFDailyPool whereResourceId($value)
 * @method static Builder|IFDailyPool whereStartAt($value)
 * @method static Builder|IFDailyPool whereTtl($value)
 * @method static Builder|IFDailyPool whereUpdatedAt($value)
 * @mixin Eloquent
 */
class IFDailyPool extends IFPeriodPool {
    /**
     * @var string $period
     */
    protected $period = 'Daily';

    /**
     * @var string $table
     */
    protected $table = 'if_daily';
}
