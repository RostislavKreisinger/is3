<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IFImportPool
 *
 * @package App\Model\ImportPools
 * @property int $id
 * @property bool $active
 * @property bool $ttl
 * @property int $project_id
 * @property int $resource_id
 * @property string $unique
 * @property string $date_from
 * @property string $date_to
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
 * @method static Builder|IFImportPool whereActive($value)
 * @method static Builder|IFStepPool whereActiveIn($active)
 * @method static Builder|IFImportPool whereCreatedAt($value)
 * @method static Builder|IFImportPool whereDateFrom($value)
 * @method static Builder|IFImportPool whereDateTo($value)
 * @method static Builder|IFImportPool whereDelayCount($value)
 * @method static Builder|IFImportPool whereDeletedAt($value)
 * @method static Builder|IFImportPool whereFinishAt($value)
 * @method static Builder|IFImportPool whereHostname($value)
 * @method static Builder|IFImportPool whereId($value)
 * @method static Builder|IFStepPool whereOlderThan($datetime)
 * @method static Builder|IFImportPool wherePid($value)
 * @method static Builder|IFImportPool whereProjectId($value)
 * @method static Builder|IFImportPool whereResourceId($value)
 * @method static Builder|IFImportPool whereStartAt($value)
 * @method static Builder|IFImportPool whereTtl($value)
 * @method static Builder|IFImportPool whereUnique($value)
 * @method static Builder|IFImportPool whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|IFStepPool notDelayed()
 */
class IFImportPool extends IFStepPool {
    /**
     * @var string $table
     */
    protected $table = 'if_import';

    /**
     * @var string $ifStep
     */
    protected $ifStep = 'Import';
}
