<?php

namespace App\Http\Controllers\Open\Monitoring\Pricing\Stream;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Monkey\Dotenv\DotEnvHelper;
use Monkey\Environment\Environment;
use Monkey\View\View;

abstract class BaseController extends \App\Http\Controllers\Open\Monitoring\BaseController {

    const EXTERNAL_ANALYTICS_LOCAL = "http://localhost/external-analytics/public/internal/";
    const EXTERNAL_ANALYTICS = "https://staging.monkeydata.cloud/monitor/external-analytics/production/0/internal/";



    /**
     * @return mixed
     */
    public function getIndex() {
        $fontSize = Input::get("fontSize", '1em');
        View::share("fontSize", $fontSize);

        $generatedHash = DotEnvHelper::get("ExternalAnalytics:internalGeneratedHash", null);
        View::share("baseAddress", $this->getAddress().$generatedHash);

        $this->setPageRefresh(10000);
        return $this->action($this->getRequest());
    }

    /**
     * @return string
     */
    private function getAddress() {
        if(Environment::isLocalhost()){
            return self::EXTERNAL_ANALYTICS_LOCAL;
        }
        return self::EXTERNAL_ANALYTICS;
    }

    abstract protected function action(Request $request);

}