<?php

namespace App\Services;


use App\Model\ImportPools\IFDailyPool;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

/**
 * Class BrokenFlowService
 * @package App\Services
 */
class BrokenFlowService {
    /**
     * @return Collection
     */
    public static function get(): Collection {
        return IFDailyPool::query()
            ->join('if_import', function (JoinClause $join) {
                $join->on('if_import.id', '=', 'if_daily.if_import_id')
                    ->on('if_import.project_id', '!=', 'if_daily.project_id');
            })
            ->get([
                'if_import_id',
                'if_daily.project_id AS daily_project_id',
                'if_daily.active AS daily_active',
                'if_import.project_id AS import_project_id',
                'if_import.active AS import_active',
            ]);
    }
}