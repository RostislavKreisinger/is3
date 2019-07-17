<?php

namespace App\Http\Controllers\ProjectIco;


use App\Helpers\ProjectIco\EshopProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\URL;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\View\View;
use Throwable;

class ProjectListController extends AController {

    public function getIndex(Request $request) {

        $justSkipped = (bool) $request->exists("skipped");

        $limit = $request->get("limit", 5);
        $limit = min($limit, 20);
        $limit = max(1, $limit);
        $projectsQuery = MDDatabaseConnections::getImportSupportConnection()->table("project_ico");

        // vde(MDDatabaseConnections::getImportSupportConnection()->getMySQLServer()->getDSN());

        if($justSkipped === false) {
            $projectsQuery->where(function (Builder $where) {
                $where->whereRaw("skip_until_at < NOW()");
                $where->orWhereNull("skip_until_at");
            });
        }else{
            $projectsQuery->whereNotNull("skip_until_at");
        }
        $projectsQuery->where(function (Builder $where){
            $where->orWhereNull("ico");
            $where->orWhereNull("nationality");
            $where->orWhereNull("is_self_employed");
        });
        $projectsQuery->orderByRaw("RAND()");
        $projectsQuery->take($limit);

        $projects = $projectsQuery->get();

        View::share("projects", $projects);
        View::share("baseUrl", URL::to('open/project-ico'));


        $showStats = (bool) $request->exists("stats");
        View::share("showStats", $showStats);
        if($showStats){
            $statsQuery = MDDatabaseConnections::getImportSupportConnection()->table("project_ico");
            $data = $statsQuery->selectRaw("COUNT(*) as `all`, SUM(IF(ico is not null AND nationality is not null AND is_self_employed is not null, 1, 0)) as filled, SUM(IF( skip_until_at is not null, 1, 0)) as skipped")->first();
            $stats = (object) [
                "all" => $data->all,
                "filled" => $data->filled,
                "skipped" => $data->skipped,
                "done" => $data->filled + $data->skipped,
                "left" => $data->all - $data->filled - $data->skipped,
                "percent" => round((($data->filled + $data->skipped)/$data->all)*100, 2)
            ];
            View::share("stats", $stats);
        }



    }

    public function postIco(Request $request) {
        try {
            $data = $this->_postIco($request);
        } catch (Throwable $exception) {
            return JsonResponse::create(["message" => $exception->getMessage()], 400);
        }
        return JsonResponse::create();
    }

    public function postSkip(Request $request) {
        try {
            $data = $this->_postSkip($request);
        } catch (Throwable $exception) {
            return JsonResponse::create(["message" => $exception->getMessage()], 400);
        }
        return JsonResponse::create();
    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function _postIco(Request $request) {
        $eshopID = $this->getProjectId($request);
        $provider = new EshopProvider($eshopID);

        $ico = $request->get("ico", null);
        if ($ico !== null) {
            $provider->updateIco($ico);
        }

        $nationality = $request->get("nationality", null);
        if ($nationality !== null) {
            $provider->updateNationality($nationality);
        }

        $is_self_employed = $request->get("is_self_employed", null);
        if ($is_self_employed !== null) {
            $provider->updateIsSelfEmployed($is_self_employed);
        }

    }

    /**
     * @param Request $request
     * @throws Exception
     */
    private function _postSkip(Request $request) {
        $eshopID = $this->getProjectId($request);
        $provider = new EshopProvider($eshopID);
        $provider->skip();
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Monkey\Helpers\Exceptions\HelpersException
     * @throws Exception
     */
    private function getProjectId(Request $request) {
        $eshopAID = $request->route("projectAID");
        if ($eshopAID === null) {
            throw new Exception("Missing attribute projectAID");
        }
        // $eshopAID = Strings::alpha2id($eshopAID);
        return $eshopAID;
    }



}