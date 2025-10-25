<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/add', name: 'author_addAuthor')]
    public function addAuthor(EntityManagerInterface $mr): Response
    {
        // $manager = $mr->getManager()->getRepository(Author::class)->findAll();;
        $author = new Author();
        $author->setUsername("anasfdl");
        $author->setEmail("anas.chagour@gmail.com");
        //$author->setNbBook(50);
        // if ($author) {
        $mr->persist($author);
        $mr->flush();
        // return new Response('added with succes');
        // }

        return $this->redirectToRoute('author_getAuthors');
    }
     #[Route('/authors', name: 'author_getAuthors')]
    public function getAuthors(AuthorRepository $authRepo, Request $req): Response
    {
        $name = $req->query->get('author');
        if ($name) {
            $authors = $authRepo->findByName($name);
        } else {
            $authors = $authRepo->findAll();
        }

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
            'name' => $name
        ]);
    }
#[Route('/insert', name: 'author_insertAuthor')]
    public function insertAuthor(EntityManagerInterface $mr, Request $request): Response
    {   // objet ou mettre les données
        $author = new Author();
        //$book = new Book();
        //$book->setTitle('les amis');
        //$book->setPublished(true);
        //$author->addBook($book);
        // créer un formulaire et l'associer à l'objet $author
        $form = $this->createForm(AuthorType::class, $author);

        //remplir l'objet $author à partir du request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mr->persist($author); // générer le code sql sans exécution dans doctrine
            $mr->flush(); // exécuter la requette dans la BD

            return $this->redirectToRoute('author_getAuthors');
        }

        return $this->render('author/form.html.twig', [
            'authorForm' => $form,
        ]);
    }
 #[Route('/update/{id}', name: 'author_updateAuthor')]
    public function updateAuthor(EntityManagerInterface $mr, Request $request, $id): Response
    {
        $author = new Author();

        // get author By id to be updated
        $author = $mr->getRepository(Author::class)->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $mr->persist($author);
            $mr->flush();

            return $this->redirectToRoute('author_getAuthors');
        }

        return $this->render('author/form.html.twig', [
            'authorForm' => $form,
        ]);
    }
 #[Route('/delete/{id}', name: 'author_delete')]
    public function delete(EntityManagerInterface $mr, $id): Response
    {
        $author = $mr->getRepository(Author::class)->find($id);
        $mr->remove($author);
        $mr->flush();

        return $this->redirectToRoute('author_getAuthors');
    }
#[Route('/authors/delete-no-books', name: 'author_delete_no_books')]
public function deleteAuthorsWithNoBooks(EntityManagerInterface $em, AuthorRepository $authorRepo): Response
{
    $authors = $authorRepo->findAuthorsWithNoBooks();
    if (count($authors) === 0) {
        $this->addFlash('info', 'No authors to delete.');
        return $this->redirectToRoute('author_getAuthors');
    }
    foreach ($authors as $author) {
        $em->remove($author);
    }
    $em->flush();

    $this->addFlash('success', count($authors) . ' author(s) deleted.');
    return $this->redirectToRoute('author_getAuthors');
}
#[Route('/authors/search-books', name: 'author_search_books')]
#[Route('/authors/search-books', name: 'author_search_books')]
public function searchByBookCount(Request $req, AuthorRepository $authRepo): Response
{
    $form = $this->createForm(\App\Form\AuthorSearchType::class);
    $form->handleRequest($req);

    $authors = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $min = $data['min'];
        $max = $data['max'];

        $authors = $authRepo->findByBookCountRange($min, $max);
    }

    return $this->render('author/list.html.twig', [
        'form' => $form->createView(),
        'authors' => $authors,
        'name' => '', // ✅ Ajout ici pour éviter l'erreur Twig
    ]);
}

}
