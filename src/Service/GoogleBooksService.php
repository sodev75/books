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

namespace App\Service;

/**
 * Class GoogleBooksService
 * @package AppBundle\Service
 */
class GoogleBooksService
{
    private $client;

    private $service;

    /**
     * GoogleBooksService constructor.
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
        $this->client->addScope(\Google_Service_Books::BOOKS);
        $this->service = new \Google_Service_Books($this->client);
        $this->getTokenAuthorizeYoutube();
    }

    /** generate url for client oauth or valid token response
     * @return array
     */
    public function getTokenAuthorize()
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

    /** authenticate google service
     * @param string $code
     * @param string $state
     */
    public function authenticate(string $code, string $state)
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

    public function searchBookBy()
    {

    }

    public function searchBookById(integer $id)
    {

    }

    public function searchBookByIsbn(integer $id)
    {

    }



}