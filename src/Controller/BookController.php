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
        $books = $this->getDoctrine()->getRepository(Book::class)->findBy(['isInTheLibrary' => true]);
        return $this->render('book/list.html.twig', [
            "books" => $books
        ]);
    }

    /**
     * @Route("/book/wishlist", name="wishlist books")
     */
    public function wishListBookLibrary()
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findBy(['isInTheLibrary' => false]);
        return $this->render('book/list.html.twig', [
            "books" => $books
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
     * @Route("/books/{id}", name="view book", requirements={"id": "\d+"})
     */
    public function viewBook(Request $request)
    {
        $id = $request->get('id');
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        if(!$book){
            return $this->redirectToRoute('list books');
        }
        return $this->render('book/view.html.twig', [
            'book' => $book,
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
                $wishBook = new Book();
                $wishBook->setGoogleBooksId($volumeId);
                $wishBook->setTitle($book->getVolumeInfo()->getTitle());
                $wishBook->setPublisher($book->getVolumeInfo()->getPublisher());
                $authors= null;
                foreach ($book->getVolumeInfo()->getAuthors() as $author) {
                    $authors .= $author.", ";
                }
                $wishBook->setAuthor($authors);
                $wishBook->setMainCategory($book->getVolumeInfo()->getCategories()[0]);
                $wishBook->setisInTheLibrary(false);
                $wishBook->setLinkSmallImageBook($book->getVolumeInfo()->getImageLinks()->getSmall());
                $wishBook->setPageCount($book->getVolumeInfo()->getPageCount());
                $wishBook->setDescription($book->getVolumeInfo()->getDescription());
                $wishBook->setLanguage($book->getVolumeInfo()->getLanguage());
                $wishBook->setProposedBy('Sophie B');
                $this->getDoctrine()->getManager()->persist($wishBook);
                $this->getDoctrine()->getManager()->flush();
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
