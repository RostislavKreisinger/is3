<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Services\BrokenFlowService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;

/**
 * Class BrokenFlowsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class BrokenFlowsController extends Controller {
    /**
     * @return Collection
     */
    public function index(): Collection {
        return BrokenFlowService::get();
    }
}