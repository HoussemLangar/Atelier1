<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/service/{id}', name: 'showservice')]
    public function serviceByID($id): Response
    {
        return $this->render('service/showservice.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/home', name: 'homeservice')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_service');
    }
}
