<?php

namespace App\Http\Controllers\Open\Monitoring\Onboarding\Platform;

use Illuminate\Http\JsonResponse;
use Monkey\DateTime\DateTimeHelper;
use Monkey\Helpers\Arrays;
use Monkey\View\View;

class RegistrationController extends BaseController {

    public function getData() {
        $projects = $this->getShoptetProjects();


        // IMPORTANT
        foreach ($projects as $project){
            if($project->rs_active == 3){
                $project->important = 1;
            }

            $project->created_at = (new DateTimeHelper($project->created_at))->format("d.m. h:i");
        }

        $responseProjects = Arrays::limit($projects, 50);

        View::share("projectsCount", count($projects));
        View::share("projects", $responseProjects);

        View::share("projectsCounts", $this->getProjectPlatformCounts($projects));


        $response = new JsonResponse();
        $response->setData(array(
            "html" => $this->getView()->render(),
            "projectCount" => count($projects)
        ));
        return $response;
    }



}