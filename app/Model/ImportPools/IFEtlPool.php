<?php

namespace App\Model\ImportPools;


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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $hostname
 * @property int|null $pid
 * @property string|null $deleted_at
 * @property-read \App\Model\ImportPools\IFControlPool $controlPool
 * @property-read string $if_step
 * @property-read \App\Model\Project $project
 * @property-read \App\Model\Resource $resource
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool delayed()
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereActiveIn($active)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereDelayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereOlderThan($datetime)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFEtlPool whereUpdatedAt($value)
 * @mixin \Eloquent
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