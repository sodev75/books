<?php

namespace App\Controller;

use App\Service\GoogleBooksService;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function addWishListBookLibrary(Request $request, GoogleBooksService $booksService)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $booksSearchResults= null;

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()){
                /** @var Book $bookSearch */
                $bookSearch = $form->getData();
                $title = $bookSearch->getTitle();
                $author = $bookSearch->getAuthor();
                $publisher = $bookSearch->getPublisher();

                if ($title){
                    $booksSearchResults = $booksService->searchListofBookByTitle($title);
                }
                if ($author){
                    $booksSearchResults = $booksService->searchListofBookByAuthor($author);
                }
                if ($publisher){
                    $booksSearchResults = $booksService->searchListofBookByAuthor($publisher);
                }
                if (($title && $author) || ($title && $publisher) || ($author && $publisher) ){
                    $booksSearchResults = $booksService->searchListofBookByMultiple($title, $author, $publisher);
                }
            }

        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
            'booksSearchResults' => $booksSearchResults
        ]);


    }


    /**
     * @Route("/book/{id}", name="view book", requirements={"id": "\d+"})
     */
    public function viewBook(Request $request)
    {
        $id = $request->get('id');
        //$book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $wishBook = new Book();
        $wishBook->setTitle('test');
        $wishBook->setPublisher('test2');
        $wishBook->setMainCategory('Test3');
        $wishBook->setisInTheLibrary(false);
        $wishBook->setPageCount(500);
        $wishBook->setSubject('TEst4');
        $wishBook->setProposedBy('Sophie');
        return $this->render('book/view.html.twig', [
            'book' => $wishBook,
        ]);
    }


    /**
     * Ajax search books
     * @Route("/book/search", name="search_books")
     */
    public function searchBook(Request $request, GoogleBooksService $booksService)
    {
        if ($request->isXmlHttpRequest()) {

            $volumeId = $request->get('volumeId');
            if ($volumeId) {
                $book = $booksService->searchBookById($volumeId);
                var_dump($book->getVolumeInfo()->getPageCount()); die;
                $wishBook = new Book();
                $wishBook->setGoogleBooksId($volumeId);
                $wishBook->setTitle($book->getVolumeInfo()->getT);
                $wishBook->setPublisher();
                $wishBook->setMainCategory();
                $wishBook->setisInTheLibrary(false);
                $wishBook->setLinkSmallImageBook();
                $wishBook->setPageCount($book->getVolumeInfo()->getPageCount());
                $wishBook->setSubject($book->getVolumeInfo()->getDe());
                //$wishBook->setProposedBy();
                $response = [
                    'success'       => true,
                    'volumeId'     => $volumeId,
                ];
                return new JsonResponse($response, 200);
            }
        }
        else{
            return new JsonResponse('unauthorized', 401);
        }

    }
}
