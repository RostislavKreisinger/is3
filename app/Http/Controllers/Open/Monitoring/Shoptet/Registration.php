<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;


use Illuminate\Database\Query\JoinClause;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\View\View;

class Registration extends BaseController {

    public function getIndex() {
        $projects = $this->getShoptetProjects();

        View::share("projectsCount", count($projects));
        View::share("projects", $projects);
    }



}