<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexController;
use Monkey\Config\Application\ProjectEndpointBaseUrl;
use Monkey\Connections\MDDatabaseConnections;

/**
 * Class BaseController
 * @package App\Http\Controllers\Homepage
 */
class BaseController extends Controller {
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
        $this->getView()->addParameter('baseApiUrl', ProjectEndpointBaseUrl::getInstance()->getImportSupportApiUrl());
        $this->getView()->addParameter('apiToken', $this->getApiToken());
    }

    /**
     * TODO: Změnit způsob získávání tokenu
     * 
     * @return string
     */
    private function getApiToken(): string {
        $tokenRecord = MDDatabaseConnections::getAccountsConnection()->table('access_token')
            ->orderBy('expires_at', 'desc')
            ->where('user_id', 15300)
            ->first(['access_token']);

        if (empty($tokenRecord)) {
            return '';
        }

        return $tokenRecord->access_token;
    }
}