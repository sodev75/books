<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/book/list", name="list books")
     */
    public function listBookLibrary()
    {
        return $this->render('book/list.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/book/list", name="wishlist books")
     */
    public function wishListBookLibrary()
    {
        return $this->render('book/list.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/book/list", name="add wishlist books")
     */
    public function addWishListBookLibrary()
    {
        return $this->render('book/add.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
}
