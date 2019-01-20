<?php

namespace App\Model\ImportPools;


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
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereActiveIn($active)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereDelayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereOlderThan($datetime)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFImportPool whereUpdatedAt($value)
 * @mixin \Eloquent
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