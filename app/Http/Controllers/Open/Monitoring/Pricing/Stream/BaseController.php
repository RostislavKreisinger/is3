<?php

namespace App\Http\Controllers\Open\Monitoring\Pricing\Stream;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Monkey\Dotenv\DotEnvHelper;
use Monkey\View\View;

abstract class BaseController extends \App\Http\Controllers\Open\Monitoring\BaseController {

    const EXTERNAL_ANALYTICS = "http://localhost/external-analytics/public/internal/";



    /**
     * @return mixed
     */
    public function getIndex() {
        $fontSize = Input::get("fontSize", '1em');
        View::share("fontSize", $fontSize);

        $generatedHash = DotEnvHelper::get("ExternalAnalytics:internalGeneratedHash", null);
        View::share("baseAdrress", self::EXTERNAL_ANALYTICS.$generatedHash);



        $this->setPageRefresh(10000);
        return $this->action($this->getRequest());
    }


    abstract protected function action(Request $request);

}