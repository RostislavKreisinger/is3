<?php

namespace App\Http\Controllers\Database\Universal;

use App\Http\Controllers\Database\AController;
use DB;
use Exception;
use Illuminate\Support\Facades\Input;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\Vardump\VardumpQuery;
use Monkey\View\View;

class DatabaseSelectorController extends AController {


    const ENABLED_DATABASE = [
//        'monkeydata',
        'md_order_alert_dw'
    ];


    public function getIndex($database = null) {
        $view = $this->getView();

        $view->addParameter('databases', self::ENABLED_DATABASE);
        $view->addParameter('selectedDatabase', $database);

        $query = Input::get('query');

        $vd = new VardumpQuery();
        $vd->setShowBacktrace(false);
        $vd->setEndOfLine("\n");
        $view->addParameter('query', $vd->formatSqlQuery($query));
    }

    /**
     * @param null $database
     * @return View|string
     * @throws Exception
     */
    public function postIndex($database = null) {
        if ($database === null) {
            return "Choose database";
        }

        $connection = $this->resolveConnection($database);
        $view = new View("default.database.universal.output");
        try {
            $query = Input::get('query', false);
            if (empty($query)) {
                return "Empty query";
            }
            $data = $connection->select(DB::raw($query));
            $view->with('data', $data);
        } catch (\Throwable $e) {
            $view->with('error', $e);
        }
        return $view;
    }

    /**
     * @param $database
     * @return \Monkey\Connections\Extension\LaravelMySqlConnection
     * @throws Exception
     */
    private function resolveConnection($database) {
        switch ($database) {
            case "md_order_alert_dw":
                return MDOrderAlertConnections::getOrderAlertDwConnection();
//            case "monkeydata":
//                return MDDatabaseConnections::getMasterAppConnection();

        }
        throw new Exception("Unexpected database name");
    }


}
