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
            "books" => $books,
            'title' => "Library books"
        ]);
    }

    /**
     * @Route("/book/wishlist", name="wishlist books")
     */
    public function wishListBookLibrary()
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findBy(['isInTheLibrary' => false]);
        return $this->render('book/list.html.twig', [
            "books" => $books,
            'title' => "Wishlist books"
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
            if ($form->isValid()) {
                /** @var Book $bookSearch */
                $bookSearch = $form->getData();
                $title = $bookSearch->getTitle();
                $author = $bookSearch->getAuthor();
                $publisher = $bookSearch->getPublisher();

                if ($title) {
                    $booksSearchResults = $booksService->searchListofBookByTitle($title);
                }
                if ($author) {
                    $booksSearchResults = $booksService->searchListofBookByAuthor($author);
                }
                if ($publisher) {
                    $booksSearchResults = $booksService->searchListofBookByAuthor($publisher);
                }
                if (($title && $author) || ($title && $publisher) || ($author && $publisher)) {
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
        if (!$book) {
            return $this->redirectToRoute('list books');
        }
        return $this->render('book/view.html.twig', [
            'book' => $book
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
                $title = $book->getVolumeInfo()->getTitle();
                $wishBook->setTitle($title);
                $wishBook->setPublisher($book->getVolumeInfo()->getPublisher());
                $authors= null;
                $i=0;
                foreach ($book->getVolumeInfo()->getAuthors() as $author) {
                    $i++;
                    if (count($book->getVolumeInfo()->getAuthors()) == 1) {
                        $authors = $author;
                    } else {
                        if (count($book->getVolumeInfo()) == $i) {
                            $authors .= $author;
                        } else {
                            $authors .= $author.", ";
                        }
                    }
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
                    'msg'       => "$title a bien été ajouté à la wishlist"
                ];
                return new JsonResponse($response, 200);
            }
        } else {
            return new JsonResponse('unauthorized', 401);
        }
    }

    /**
     * Ajax add book in library
     * @Route("/book/addinlibrary", name="add_book_inlibrary")
     */
    public function addBookInLibrary(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('id');
            if ($id) {
                $book = $this->getDoctrine()->getManager()->getRepository(Book::class)->find($id);
                $book->setisInTheLibrary(true);
                $title = $book->getTitle();
                $this->getDoctrine()->getManager()->persist($book);
                $this->getDoctrine()->getManager()->flush();
                $response = [
                    'success'       => true,
                    'id'     => $id,
                    'msg'       => "$title a bien été ajouté à la bibliothèque"
                ];
                return new JsonResponse($response, 200);
            }
        } else {
            return new JsonResponse('unauthorized', 401);
        }
    }

    /**
     * Ajax add book in library
     * @Route("/book/delete/{id}", name="delete_book", requirements={"id": "\d+"})
     */
    public function deleteBook(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get('id');
            if ($id) {
                $book = $this->getDoctrine()->getManager()->getRepository(Book::class)->find($id);
                $title = $book->getTitle();
                $this->getDoctrine()->getManager()->delete($book);
                $this->getDoctrine()->getManager()->flush();
                $response = [
                    'success'       => true,
                    'id'     => $id,
                    'msg'       => "$title a bien été supprimé"
                ];
                return new JsonResponse($response, 200);
            }
        } else {
            return new JsonResponse('unauthorized', 401);
        }
    }
}
