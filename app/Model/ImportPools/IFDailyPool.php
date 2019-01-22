<?php

namespace App\Model\ImportPools;


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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $unique
 * @property-read \App\Model\Project $project
 * @property-read \App\Model\Resource $resource
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereIfImportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereNextRunDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFDailyPool whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IFDailyPool extends IFPeriodPool {
    /**
     * @var string $table
     */
    protected $table = 'if_daily';
}