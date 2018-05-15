<?php

namespace App\Helpers\Inside;


use Monkey\Config\OAuth2\Inside\InsideOAuth2SecretTokenConfiguration;
use Monkey\Connections\MDDatabaseConnections;
use Monkey\Oauth2Api\Oauth2ApiAccessTokenClient;
use Monkey\Oauth2Api\Oauth2ApiVerify;
use Monkey\Oauth2Client\Exceptions\Oauth2ClientException;
use Monkey\Oauth2Client\Oauth2Curl;
use Monkey\Oauth2Client\UserAccessTokenClient;
use Monkey\Oauth2SsoUser\Interfaces\ISsoUser;
use Monkey\Oauth2Token\Interfaces\IAccessToken;
use Monkey\Oauth2Verify\Exceptions\Oauth2VerifyUnauthorizedException;

class InsideTokenRefresher {


    /**
     * @param $userId
     * @throws Oauth2ClientException
     * @throws \Exception
     */
    public function refreshSecret($userId) {

        $user = $this->getSsoUser($userId);

        $userEmail = $user->getEmail();

        $secretToken = $this->getSecretToken($userId);


        MDDatabaseConnections::getMiddlewareInsideConnection()->table("eshop_identity")
            ->where("email", '=', $userEmail)
            ->update(["secret_token" => $secretToken->toJSON(), "iframe_token" => null, "algorithm" => null]);

        $projects = MDDatabaseConnections::getInsideConnection()->table("project")
            ->where("user_id", '=', $user->getId())
            ->get();
        if(count($projects) != 1){
            throw new \Exception("Wrong project count");
        }

        $project = $projects[0];

        MDDatabaseConnections::getInsideConnection()->table("project")
            ->where("id", '=', $project->id)
            ->update(["secret_token" => hash("sha256", $secretToken->getAccessToken())]);

    }

    /**
     * @return ISsoUser
     * @throws \Exception
     */
    final public function getSsoUser($userId) {
        try {
            $oauth2ApiAccessTokenClient = new Oauth2ApiAccessTokenClient($userId);
            $accessToken = $oauth2ApiAccessTokenClient->getToken();
        } catch (Oauth2ClientException $exception) {
            throw $exception;
        }

        try {
            $oauth2ApiVerify = new Oauth2ApiVerify();
            $verifyResponse = $oauth2ApiVerify->verify($accessToken->getAccessToken());
        } catch (Oauth2VerifyUnauthorizedException $exception) {
            throw $exception;
        }

        return $verifyResponse->getUser();
    }



    /**
     * @param int $userId
     * @return IAccessToken
     * @throws Oauth2ClientException
     */
    private function getSecretToken($userId) {
        $curl = new Oauth2Curl();

        $clientId = InsideOAuth2SecretTokenConfiguration::getInstance()->getClientId();
        $clientSecret = InsideOAuth2SecretTokenConfiguration::getInstance()->getClientSecret();

        // TODO zprisnit a dodelat na AUTH
//        $redirectUri = \URL::current();

        $userAccessTokenClient = new UserAccessTokenClient($curl, $clientId, $clientSecret, $userId);

        return $userAccessTokenClient->getToken();
    }
    
}