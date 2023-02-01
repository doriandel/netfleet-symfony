<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Film;
use App\Form\FilmType;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    

    #[Route('/home', name: 'app_home')]
    public function index()
    {
        $form = $this->createForm(FilmType::class);
        return $this->render('home.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
{
    $film = new Film();
    $form = $this->createForm(FilmType::class, $film);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $doctrine ->getManager();
        $entityManager->persist($film);
        $entityManager->flush();
        return new Response('', Response::HTTP_CREATED);
    }
    else return new Response('', Response::HTTP_BAD_REQUEST);
}

}
