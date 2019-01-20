<?php

namespace App\Model\ImportPools;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $unique
 * @property-read \App\Model\Project $project
 * @property-read \App\Model\Resource $resource
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereIfImportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereOutputDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereResourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IFHistoryPool whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IFHistoryPool extends IFPeriodPool {
    /**
     * @var string $table
     */
    protected $table = 'if_history';
}