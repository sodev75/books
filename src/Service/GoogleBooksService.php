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
    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setAccessType("offline");
        $this->client->setApprovalPrompt("force");
        $this->client->addScope(\Google_Service_Books::BOOKS);
        $this->service = new \Google_Service_Books($this->client);
    }

    public function searchListofBookByTitle(string $title)
    {
        $results = $this->service->volumes->listVolumes('intitle:'.$title, []);
        return $results;

    }
    public function searchListofBookByAuthor(string $author)
    {
        $results = $this->service->volumes->listVolumes('inauthor:'.$author, []);
        return $results;

    }
    public function searchListofBookByMultiple(string $title, string $author=null, string $publisher=null)
    {
        $query= null;
        if($title){
            $query .= 'intitle:'.$title.';';
        }
        if($author){
            $query .= 'inauthor:'.$author.';';
        }
        if($publisher){
            $query .= 'inpublisher:'.$publisher.';';
        }
        $results = $this->service->volumes->listVolumes($query, []);
        return $results;

    }

    public function searchBookById(string $id)
    {
        $result = $this->service->volumes->get($id);
        return $result;
    }




}