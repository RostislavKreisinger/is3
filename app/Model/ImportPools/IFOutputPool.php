<?php

namespace App\Model\ImportPools;


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
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereActiveIn($active)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereDelayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFStepPool whereOlderThan($datetime)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereUnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFOutputPool whereUpdatedAt($value)
 * @mixin \Eloquent
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