<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Book;
use App\Form\BookType;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_index')]
    public function index(BookRepository $repo): Response
    {
        $books = $repo->findBy(['published' => true]);
        $publishedCount = $repo->count(['published' => true]);
        $unpublishedCount = $repo->count(['published' => false]);

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'publishedCount' => $publishedCount,
            'unpublishedCount' => $unpublishedCount
        ]);
    }

    #[Route('/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $book->setPublished(true);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'book_edit')]
    public function edit(Request $request, Book $book, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'book_delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $author = $book->getAuthor();
        $em->remove($book);
        $author->setNbBooks(max(0, $author->getNbBooks() - 1));

        $em->flush();
        return $this->redirectToRoute('book_index');
    }

    #[Route('/delete-authors-without-books', name: 'delete_authors_without_books')]
    public function deleteAuthorsWithoutBooks(EntityManagerInterface $em, AuthorRepository $repo): Response
    {
        $authors = $repo->findBy(['nbBooks' => 0]);
        foreach ($authors as $author) {
            $em->remove($author);
        }
        $em->flush();

        return $this->redirectToRoute('author_index');
    }

    #[Route('/{id}', name: 'book_show')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }
}
