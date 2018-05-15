<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is licensed exclusively to Matters Studio.
 *
 * @copyright  Copyright (c) 2017 Matters Studio (https://matters.tech)
 * @license      All rights reserved
 * @author      Matters Studio (https://matters.tech)
 *
 */

namespace AppBundle\Service;

/**
 * Class YoutubeService
 * @package AppBundle\Service
 */
class YoutubeService
{
    private $client;

    private $service;

    /**
     * YoutubeService constructor.
     * @param string $clientId
     * @param string $clientSecret
     */
    public function __construct(string $clientId, string $clientSecret)
    {
        $this->client = new \Google_Client();
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setAccessType("offline");
        $this->client->setApprovalPrompt("force");
        $this->client->addScope(\Google_Service_YouTube::YOUTUBE);
        $this->service = new \Google_Service_YouTube($this->client);
        $this->getTokenAuthorizeYoutube();
    }

    /** generate url for client oauth or valid token response
     * @return array
     */
    public function getTokenAuthorizeYoutube()
    {
        $response = [];
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']."/admin/config/youtube/successtoken",
            FILTER_SANITIZE_URL);
        $this->client->setRedirectUri($redirect);
        $tokenSessionKey = 'token-' . $this->client->prepareScopes();
        if (isset($_GET['code'])) {
            if (strval($_SESSION['state']) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }

            $this->client->authenticate($_GET['code']);
            $_SESSION[$tokenSessionKey] = $this->client->getAccessToken();
            header('Location: ' . $redirect);
        }

        if (isset($_SESSION[$tokenSessionKey])) {
            $this->client->setAccessToken($_SESSION[$tokenSessionKey]);
        }


        if (!$this->client->getAccessToken()){
            $state = mt_rand();
            $this->client->setState($state);
            $_SESSION['state'] = $state;
            $response['tokenValid'] = false;
            $response['response'] = $this->client->createAuthUrl();
            return $response;
        }
        else{
            $this->client->setAccessToken($this->client->getAccessToken());
            $response['tokenValid'] = true;
            $response['response'] = "Votre authorization token est toujours valable";
            return $response;
        }

    }

    /** authenticate youtube
     * @param string $code
     * @param string $state
     */
    public function authenticateYoutube(string $code, string $state)
    {
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']."/admin/config/youtube/successtoken",
            FILTER_SANITIZE_URL);

        if (isset($code)) {
            try{
                $this->client->setRedirectUri($redirect);
                $auth = $this->client->authenticate($_GET['code']);
                $accessToken = $this->client->getAccessToken();
                $this->client->setAccessToken($accessToken);
            }
            catch(InvalidArgumentException $exception){
                echo $exception->getMessage(); die;
            }
        }

    }

    /** get a list of live streams
     * @return array|mixed
     */
    public function getLiveStream()
    {
        if ($this->client->getAccessToken()) {
            var_dump($this->service);

            $streamsResponse = $this->service->liveStreams->listLiveStreams('id,snippet', [
                "mine" => true
            ]);
            $response = $streamsResponse['items'];
        }
        else{
            $response = $this->client->getAccessToken();
        }

        return $response;
    }

    /** get a list of live broadcast (event stream)
     * @return array|mixed
     */
    public function getLiveBroadCast()
    {
        if ($this->client->getAccessToken()) {

            $streamsResponse = $this->service->liveBroadcasts->listLiveBroadcasts('id,snippet', array(
                "mine" => true
            ));
            //var_dump($streamsResponse);
            $response = $streamsResponse['items'];
        }
        else{
            $response = $this->client->getAccessToken();
        }

        return $response;
    }

    /** get last created live  broadcast
     * @return array
     */
    public function getLastLiveBroadCast(){

        if ($this->client->getAccessToken()) {

            $broadcast = $this->service->liveBroadcasts->listLiveBroadcasts('id,snippet', array(
                "mine" => true
            ));
            $response = $broadcast['items'][0];
        }
        else{
            $response = $this->client->getAccessToken();
        }
        return $response;
    }


}