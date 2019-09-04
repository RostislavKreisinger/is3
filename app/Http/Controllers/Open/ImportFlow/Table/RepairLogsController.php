<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\Logs\RepairLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class RepairLogsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class RepairLogsController extends Controller {
    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request) {
        $builder = RepairLog::query();
        $builder = $this->addFiltersToQueryBuilder(json_decode($request->get('filter', '[]')), $builder);
        $builder = $this->addSortToQueryBuilder(
            json_decode($request->get('sort', '[{"selector":"created_at","desc":true}]'), true),
            $builder
        );
        $pageSize = $request->get('take', 15);
        return $builder->paginate(
            $pageSize,
            ['*'],
            'page',
            $request->get('skip') / $pageSize + 1
        );
    }

    /**
     * @param array $filters
     * @param Builder $builder
     * @return Builder
     */
    private function addFiltersToQueryBuilder(array $filters, Builder $builder): Builder {
        foreach ($filters as $filter) {
            if (is_array($filter) && count($filter) === 3) {
                if (count($filter) === 3 && $filter[1] !== 'and') {
                    $builder = $this->addFilterToQueryBuilder($filter, $builder);
                } else {
                    $builder = $this->addFiltersToQueryBuilder($filter, $builder);
                }
            } elseif (count($filters) === 3) {
                $builder = $this->addFilterToQueryBuilder($filters, $builder);
            }
        }

        return $builder;
    }

    /**
     * @param array $filter
     * @param Builder $builder
     * @return Builder
     */
    private function addFilterToQueryBuilder(array $filter, Builder $builder): Builder {
        switch ($filter[1]) {
            case 'contains':
                $builder->where($filter[0], 'LIKE', "%{$filter[2]}%");
                break;
            case 'notcontains':
                $builder->where($filter[0], 'NOT LIKE', "%{$filter[2]}%");
                break;
            case 'startswith':
                $builder->where($filter[0], 'LIKE', "{$filter[2]}%");
                break;
            case 'endswith':
                $builder->where($filter[0], 'LIKE', "%{$filter[2]}");
                break;
            case '=':
                $builder->where($filter[0], $filter[2]);
                break;
            case '<>':
                $builder->where($filter[0], '!=', $filter[2]);
                break;
            case '<':
                $builder->where($filter[0], '<', $filter[2]);
                break;
            case '>':
                $builder->where($filter[0], '>', $filter[2]);
                break;
            case '<=':
                $builder->where($filter[0], '<=', $filter[2]);
                break;
            case '>=':
                $builder->where($filter[0], '>=', $filter[2]);
                break;
        }

        return $builder;
    }

    /**
     * @param array $sortArray
     * @param Builder $builder
     * @return Builder
     */
    private function addSortToQueryBuilder(array $sortArray, Builder $builder): Builder {
        foreach ($sortArray as $sort) {
            $builder->orderBy($sort['selector'], $sort['desc'] ? 'desc' : 'asc');
        }

        return $builder;
    }
}
