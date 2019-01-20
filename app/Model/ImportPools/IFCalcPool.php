<?php

namespace App\Model\ImportPools;


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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $hostname
 * @property int|null $pid
 * @property string|null $deleted_at
 * @property-read IFControlPool $controlPool
 * @property-read string $if_step
 * @property-read \App\Model\Project $project
 * @property-read \App\Model\Resource $resource
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool delayed()
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereActiveIn($active)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereDelayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereOlderThan($datetime)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFCalcPool whereUpdatedAt($value)
 * @mixin \Eloquent
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