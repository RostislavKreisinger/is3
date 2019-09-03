<?php


namespace App\Http\Controllers\Open\ImportFlow\Table;


use App\Model\Logs\RepairLog;
use Illuminate\Routing\Controller;

/**
 * Class RepairLogsController
 * @package App\Http\Controllers\Open\ImportFlow\Table
 */
class RepairLogsController extends Controller {
    public function index() {

        return RepairLog::query()->orderByDesc('created_at')->get();
    }
}
