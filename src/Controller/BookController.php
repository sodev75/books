<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;

class BookController extends Controller
{

    /**
     * @Route("/book/list", name="list books")
     */
    public function listBookLibrary(Request $request)
    {
        return $this->render('book/list.html.twig', [
        ]);
    }

    /**
     * @Route("/book/wishlist", name="wishlist books")
     */
    public function wishListBookLibrary()
    {
        return $this->render('book/list.html.twig', [
        ]);
    }

    /**
     * @Route("/book/add", name="add wishlist books")
     */
    public function addWishListBookLibrary()
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/book/{id}", name="view book")
     */
    public function viewBook(Request $request)
    {
        return $this->render('book/view.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }


    /**
     * Ajax search books
     * @Route("/book/search", name="search books")
     */
    public function searchBook()
    {

    }
}
