<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;


use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\View\View;

class RegistrationController extends BaseController {

    public function getData() {
        $projects = $this->getShoptetProjects();

        View::share("projectsCount", count($projects));
        View::share("projects", $projects);

        $response = new JsonResponse();
        $response->setData(array(
            "html" => $this->getView()->render(),
            "projectCount" => count($projects)
        ));
        return $response;
    }



}