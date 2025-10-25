<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book/add', name: 'book_add')]
    public function add(Request $request,EntityManagerInterface $em,AuthorRepository $authorRepo): Response {
        $book = new Book();

        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'auteur depuis le formulaire
            $author = $book->getAuthor();

            // Incrémenter nb_books
            if ($author !== null) {
                $currentCount = $author->getNb_books() ?? 0;
                $author->setNb_books($currentCount + 1);
                $em->persist($author);
            }

            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('author_getBooks');

            
        }

         return $this->render('book/add.html.twig', [
            'bookForm' => $form,
        ]);
    }
 #[Route('/books', name: 'author_getBooks')]
public function getBooks(BookRepository $bookRepo, Request $req): Response
{
    $name = $req->query->get('book');
    
    if ($name) {
        $books = $bookRepo->findBy(['title' => $name]);
    } else {
        $books = $bookRepo->findAll();
    }

    $publishedCount = $bookRepo->countByPublished(true);
    $unpublishedCount = $bookRepo->countByPublished(false);

    return $this->render('book/list.html.twig', [
        'books' => $books,
        'name' => $name,
        'publishedCount' => $publishedCount,
        'unpublishedCount' => $unpublishedCount,
    ]);
}


#[Route('/books/statsss', name: 'bookk_stats')]
public function showStats(BookRepository $bookRepo): Response
{
    $MysteryCount = $bookRepo->countByCategory('Romance');

    return $this->render('book/stats.html.twig', [
        'MysteryCount' => $MysteryCount
    ]);
}
#[Route('/books/statss', name: 'book_stats')]
public function showStatsb(BookRepository $bookRepo): Response
{
    // Récupère tous les livres pour la boucle Twig
    $books = $bookRepo->findAll();

    // Compte les livres publiés / non publiés
    $publishedCount = $bookRepo->countByPublished(true);
    $unpublishedCount = $bookRepo->countByPublished(false);

    // Envoie les données au template
    return $this->render('book/list.html.twig', [
        'books' => $books,
        'publishedCount' => $publishedCount,
        'unpublishedCount' => $unpublishedCount,
    ]);
}
#[Route('/book/delete/{id}', name: 'book_delete')]
public function deleteBook(EntityManagerInterface $mr, int $id): Response
{
    $book = $mr->getRepository(Book::class)->find($id);

    if (!$book) {
        throw $this->createNotFoundException('Book not found');
    }

    $mr->remove($book);
    $mr->flush();

    return $this->redirectToRoute('author_getBooks'); // ou 'book_getBooks' selon ta route
}

#[Route('/book/update/{id}', name: 'book_update')]
public function updateBook(EntityManagerInterface $mr, Request $request, int $id): Response
{
    $book = $mr->getRepository(Book::class)->find($id);
    if (!$book) {
        throw $this->createNotFoundException('Book not found');
    }
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $mr->persist($book);
        $mr->flush();

        return $this->redirectToRoute('author_getBooks'); 
    }
    return $this->render('book/add.html.twig', [
        'bookForm' => $form->createView(),
    ]);
}
#[Route('/book/{id}', name: 'book_show')]
public function showBook(BookRepository $bookRepo, int $id): Response
{
    $book = $bookRepo->find($id);
    if (!$book) {
        throw $this->createNotFoundException('Book not found');
    }
    return $this->render('book/show.html.twig', [
        'book' => $book,
    ]);
}
#[Route('/books/published-between', name: 'books_published_between')]
public function booksPublishedBetween(BookRepository $bookRepo): Response
{
    $start = new \DateTime('2014-01-01');
    $end = new \DateTime('2018-12-31');

    $books = $bookRepo->findPublishedBetween($start, $end);

    // Stats
    $publishedCount = $bookRepo->countByPublished(true);
    $unpublishedCount = $bookRepo->countByPublished(false);

    return $this->render('book/list.html.twig', [
        'books' => $books,
        'publishedCount' => $publishedCount,
        'unpublishedCount' => $unpublishedCount,
    ]);
}



}