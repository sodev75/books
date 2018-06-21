<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\GoogleBooksService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiBookController extends FOSRestController
{
    /**
     *
     * Post a book.
     *
     * ####Input####
     *
     *      {
     *          "isbn": "2266163744",
     *          "userId : "456"
     *      }
     *
     * ####Response####
     *
     *      {
     *          "title": "Bel-Ami",
     *          "author": "Guy de Maupassant",
     *          "publisher": "Distribooks",
     *          "isbn": "2266163744",
     *          "subject": "",
     *          "proposed_by": ""
     *          "googleBooksId": "",
     *          "pageCount": ""
     *          "linkSmallImageBook": "",
     *          "isInTheLibrary": ""
     *          "mainCategory": "",
     *      }
     *
     *
     * @param Request $request
     *
     * @Rest\View(serializerEnableMaxDepthChecks=true, statusCode=201)
     * @Rest\Post("/book", name="post book", requirements={"isbn"="\d+"})
     *
     * @return View
     */
    public function post(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $serializer = new Serializer(
            [
                new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
            ],
            [
                new JsonEncoder(),
            ]
        );

        /** @var Book $book */
        //$book  = $serializer->deserialize($request->getContent(), Book::class, 'json');
        //$infoBook = $googleBooksService->searchBookByIsbn($request->get('isbn'));


    }

    /**
     * @Route("/book/{isbn}", name="update book", requirements={"isbn"="\d+"})
     */
    public function patch(Request $request)
    {

    }
    /**
     * @Route("/book/{isbn}", name="delete book", requirements={"isbn"="\d+"})
     */
    public function delete(Request $request)
    {

    }



}
