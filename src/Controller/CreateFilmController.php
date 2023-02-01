<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Film;

class CreateFilmController extends AbstractController
{
    #[Route('/create', name: 'app_create')]
    public function create(Request $request): Response
    {
        $film = new Film();
        $film->setName($request->request->get('name'));
        $film->setSynopsis($request->request->get('synopsis'));
        $film->setType($request->request->get('type'));
        $film->setCreationDate($request->request->get('creationDate'));
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($film);
        $entityManager->flush();

        return new Response('Le film a été créé avec succès !');
    }
}
