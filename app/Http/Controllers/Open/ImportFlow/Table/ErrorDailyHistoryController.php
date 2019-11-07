<?php

namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Services\ErrorFlowService;

/**
 * Class ErrorDailyHistoryController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class ErrorDailyHistoryController extends AFlowsController {
    /**
     * Returns array of IFDailyPool and IFHistoryPool records
     *
     * @return array
     */
    public function index(): array {
        $errorFlowService = new ErrorFlowService;
        return $this->addUrls($errorFlowService->getDailyAndHistory()->all());
    }
}
