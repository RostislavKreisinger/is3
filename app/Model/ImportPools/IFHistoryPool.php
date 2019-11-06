<?php

namespace App\Model\ImportPools;


use App\Model\Project;
use App\Model\Resource;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IFHistoryPool
 *
 * @package App\Model\ImportPools
 * @property int $id
 * @property bool $active
 * @property bool $ttl
 * @property int $project_id
 * @property int $resource_id
 * @property string $date_from
 * @property string $date_to
 * @property string $output_date_to
 * @property int|null $if_import_id
 * @property string|null $start_at
 * @property string|null $finish_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $unique
 * @property-read Project $project
 * @property-read Resource $resource
 * @method static Builder|IFHistoryPool whereActive($value)
 * @method static Builder|IFHistoryPool whereCreatedAt($value)
 * @method static Builder|IFHistoryPool whereDateFrom($value)
 * @method static Builder|IFHistoryPool whereDateTo($value)
 * @method static Builder|IFHistoryPool whereDeletedAt($value)
 * @method static Builder|IFHistoryPool whereFinishAt($value)
 * @method static Builder|IFHistoryPool whereId($value)
 * @method static Builder|IFHistoryPool whereIfImportId($value)
 * @method static Builder|IFHistoryPool whereOutputDateTo($value)
 * @method static Builder|IFHistoryPool whereProjectId($value)
 * @method static Builder|IFHistoryPool whereResourceId($value)
 * @method static Builder|IFHistoryPool whereStartAt($value)
 * @method static Builder|IFHistoryPool whereTtl($value)
 * @method static Builder|IFHistoryPool whereUpdatedAt($value)
 * @mixin Eloquent
 */
class IFHistoryPool extends IFPeriodPool {
    /**
     * @var string $period
     */
    protected $period = 'History';

    /**
     * @var string $table
     */
    protected $table = 'if_history';
}
