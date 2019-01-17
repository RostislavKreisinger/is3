<?php

namespace App\Http\Controllers\Open\Monitoring;


use App\Http\Controllers\BaseViewController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Monkey\View\View;

abstract class BaseController extends BaseViewController {


    /**
     * @var Request
     */
    private $request;

    /**
     * BaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request) {
        parent::__construct();

        $this->setRequest($request);
    }




    /**
     * @return mixed
     */
    protected function isDebug() {
        return Input::exists("debug");
    }

    /**
     * @param int $pageRefresh
     */
    protected function setPageRefresh($pageRefresh) {
        View::share("pageRefresh", $pageRefresh);
    }

    /**
     * @return Request
     */
    protected function getRequest() {
        return $this->request;
    }

    /**
     * @param Request $request
     * @return BaseController
     */
    private function setRequest(Request $request) {
        $this->request = $request;
        return $this;
    }

}