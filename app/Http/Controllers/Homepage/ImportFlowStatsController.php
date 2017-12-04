<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Homepage;

use App\Model\ImportPools\CurrencyEtlCatalog;
use App\Model\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Monkey\Helpers\Strings;
use Monkey\ImportSupport\InvalidProject\ProjectRepository;
use Monkey\View\View;

/**
 * Description of HomepageController
 *
 * @author Tomas
 */
class ImportFlowStatsController extends BaseController {


    public function getIndex() {
        $importFlowStatuses = $this->getImportFlowStatuses();
        $resources = Resource::whereIn('id', $importFlowStatuses->pluck('resource_id')->toArray())->get();


        $importFlowStatuses->map(function ($importFlowStatus) use ($resources) {
            $importFlowStatus->resource = $resources->where('id', $importFlowStatus->resource_id)->first();
        });

    }


}
