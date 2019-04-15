<?php

namespace App\Http\Controllers\Homepage;


use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Auth;
use Monkey\Config\Application\ProjectEndpointBaseUrl;
use Monkey\Config\OAuth2\ImportSupport\ImportSupportOAuth2AccessTokenConfiguration;
use Monkey\Oauth2Client\Exceptions\Oauth2ClientException;
use Monkey\Oauth2Client\Oauth2Curl;
use Monkey\Oauth2Client\UserAccessTokenClient;

/**
 * Class BaseController
 * @package App\Http\Controllers\Homepage
 */
class BaseController extends Controller {
    /**
     * @throws Oauth2ClientException
     */
    public function getIndex() {
        $this->getView()->addParameter('baseUrl', action(IndexController::routeMethod('getIndex')));
        $this->getView()->addParameter('baseApiUrl', ProjectEndpointBaseUrl::getInstance()->getImportSupportApiUrl());
        $this->getView()->addParameter('apiToken', $this->getApiToken());
    }

    /**
     * @return string
     * @throws Oauth2ClientException
     */
    private function getApiToken(): string {
        $tokenClient = new UserAccessTokenClient(new Oauth2Curl(), ImportSupportOAuth2AccessTokenConfiguration::getInstance()->getClientId(), ImportSupportOAuth2AccessTokenConfiguration::getInstance()->getClientSecret(), Auth::id());
        return $tokenClient->getToken()->getAccessToken();
    }
}
