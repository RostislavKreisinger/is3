<?php

namespace App\Model\ImportPools;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class IFControlPool
 *
 * @package App\Model\ImportPools
 * @property int $id
 * @property int $project_id
 * @property int $resource_id
 * @property bool $is_history
 * @property bool $is_first_daily
 * @property bool $priority
 * @property string $date_from
 * @property string $date_to
 * @property string $run_time
 * @property string $unique
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property bool|null $in_repair
 * @property bool $workload_difficulty
 * @property string|null $flush_GFS_data_at
 * @property-read IFCalcPool $calcPool
 * @property-read IFEtlPool $etlPool
 * @property-read IFImportPool $importPool
 * @property-read IFOutputPool $outputPool
 * @property-read \App\Model\Project $project
 * @property-read \App\Model\Resource $resource
 * @method static Builder|IFControlPool whereCreatedAt($value)
 * @method static Builder|IFControlPool whereDateFrom($value)
 * @method static Builder|IFControlPool whereDateTo($value)
 * @method static Builder|IFControlPool whereDeletedAt($value)
 * @method static Builder|IFControlPool whereFlushGFSDataAt($value)
 * @method static Builder|IFControlPool whereId($value)
 * @method static Builder|IFControlPool whereInRepair($value)
 * @method static Builder|IFControlPool whereIsFirstDaily($value)
 * @method static Builder|IFControlPool whereIsHistory($value)
 * @method static Builder|IFControlPool wherePriority($value)
 * @method static Builder|IFControlPool whereProjectId($value)
 * @method static Builder|IFControlPool whereResourceId($value)
 * @method static Builder|IFControlPool whereRunTime($value)
 * @method static Builder|IFControlPool whereUnique($value)
 * @method static Builder|IFControlPool whereUpdatedAt($value)
 * @method static Builder|IFControlPool whereWorkloadDifficulty($value)
 * @mixin \Eloquent
 */
class IFControlPool extends IFPool {
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_history' => 0,
        'is_first_daily' => 0,
        'priority' => 1,
        'in_repair' => 0,
        'workload_difficulty' => 0
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'if_control';

    /**
     * IFControlPool constructor.
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(array $attributes = []) {
        self::saving(function () {
            $this->generateUnique();
        });
        parent::__construct($attributes);
    }

    /**
     * Raises workload_difficulty by 1 and updates model in DB
     *
     * @return IFControlPool
     */
    public function raiseDifficulty(): self {
        $this->workload_difficulty++;
        $this->save();
        return $this;
    }

    /**
     * Raises workload_difficulty by 1 and updates model in DB
     *
     * @return IFControlPool
     */
    public function reduceDifficulty(): self {
        if ($this->workload_difficulty > 0) {
            $this->workload_difficulty--;
            $this->save();
        }

        return $this;
    }

    /**
     * Sets is_history attribute to 0
     *
     * @return IFControlPool
     */
    public function setDaily(): IFControlPool {
        $this->is_history = 0;
        return $this;
    }

    /**
     * Sets is_history attribute to 1
     *
     * @return IFControlPool
     */
    public function setHistory(): IFControlPool {
        $this->is_history = 1;
        return $this;
    }

    /**
     * Sets is_history attribute to 2
     *
     * @return IFControlPool
     */
    public function setTester(): IFControlPool {
        $this->is_history = 2;
        return $this;
    }

    /**
     * Sets priority attribute to 0
     *
     * @return IFControlPool
     */
    public function setLowPriority(): IFControlPool {
        $this->priority = 0;
        return $this;
    }

    /**
     * Sets priority attribute to 1
     *
     * @return IFControlPool
     */
    public function setNormalPriority(): IFControlPool {
        $this->priority = 1;
        return $this;
    }

    /**
     * Sets priority attribute to 2
     *
     * @return IFControlPool
     */
    public function setHighPriority(): IFControlPool {
        $this->priority = 2;
        return $this;
    }

    /**
     * @return IFControlPool
     * @throws \Exception
     */
    private function generateUnique(): IFControlPool {
        if (empty($this->unique)) {
            $this->unique = sha1($this->project_id . $this->resource_id . random_bytes(64));
        }

        return $this;
    }

    /**
     * Sets in_repair attribute to 1 (default is 0)
     *
     * @return IFControlPool
     */
    public function setInRepair(): IFControlPool {
        $this->in_repair = 1;
        return $this;
    }

    /**
     * @return HasOne
     */
    public function importPool(): HasOne {
        return $this->hasOne(IFImportPool::class, 'unique', 'unique');
    }

    /**
     * @return HasOne
     */
    public function etlPool(): HasOne {
        return $this->hasOne(IFEtlPool::class, 'unique', 'unique');
    }

    /**
     * @return HasOne
     */
    public function calcPool(): HasOne {
        return $this->hasOne(IFCalcPool::class, 'unique', 'unique');
    }

    /**
     * @return HasOne
     */
    public function outputPool(): HasOne {
        return $this->hasOne(IFOutputPool::class, 'unique', 'unique');
    }
}