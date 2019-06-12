<?php

namespace App\Http\Controllers\Open\Monitoring\OrderAlert\Webhook;


use App\Http\Controllers\Open\Monitoring\BaseController;
use Illuminate\Http\JsonResponse;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Connections\MDOrderAlertConnections;
use Monkey\View\View;

class WebhookQueueStatsController extends BaseController {

    public function getIndex() {
       $this->setupFontSize();
       $this->setPageRefresh(5000);


    }

    public function getData() {

        // SELECT COUNT(*), MIN(created_at) as min, MAX(created_at) as max, TIMEDIFF(MIN(created_at),MAX(created_at)) FROM md_order_alert.webhook

        $query = MDOrderAlertConnections::getOrderAlertWebhookConnection()
            ->table("webhook")
            ->selectRaw("COUNT(*) as webhookCount, MIN(created_at) as minTime, MAX(created_at) as maxTime, TIMEDIFF(MIN(created_at),MAX(created_at)) as timediff");

        $data = $query->first();

        $data->timediff = $data->timediff?:"00:00:00";

        View::share("webhookData", $data);

        $response = new JsonResponse();
        $response->setData(array(
            "html" => $this->getView()->render()
        ));
        return $response;
    }

}