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

        $limit = $request->get("limit", 5);
        $limit = min($limit, 20);
        $limit = max(1, $limit);
        $projects = MDDatabaseConnections::getImportSupportConnection()
            ->table("project_ico")
            ->where(function (Builder $where){
                $where->whereRaw("skip_until_at < NOW()");
                $where->orWhereNull("skip_until_at");
            })
            ->whereNull("ico")
            ->orderByRaw("RAND()")
            ->take($limit)
             ->get()
        ;
        View::share("projects", $projects);
        View::share("baseUrl", URL::to('open/project-ico'));
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
        $ico = $request->get("ico", null);
        if ($ico === null) {
            throw new Exception("Missing attribute ico");
        }
        $provider = new EshopProvider($eshopID);
        $provider->updateIco($ico);
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