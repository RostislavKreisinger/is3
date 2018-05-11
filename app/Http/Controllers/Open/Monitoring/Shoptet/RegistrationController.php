<?php

namespace App\Http\Controllers\Open\Monitoring\Shoptet;

use Illuminate\Http\JsonResponse;
use Monkey\View\View;

class RegistrationController extends BaseController {

    public function getData() {
        $projects = $this->getShoptetProjects();


        // IMPORTANT
        foreach ($projects as $project){
            if($project->rs_active == 3){
                $project->important = 1;
            }
        }

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