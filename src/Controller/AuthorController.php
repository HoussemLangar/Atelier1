<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use App\Form\AuthorType;

class AuthorController extends AbstractController
{

    #[Route('/author', name: 'author_index')]
    public function index(AuthorRepository $repo): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $repo->findAll()
        ]);
    }

   #[Route('/author/new', name: 'author_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/author/{id}/edit', name: 'author_edit')]
    public function edit(Request $request, Author $author, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/author/{id}/delete', name: 'author_delete')]
    public function delete(Author $author, EntityManagerInterface $em): Response
    {
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('author_index');
    }

}
