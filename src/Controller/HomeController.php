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
            return new Response('Le film a été ajouté avec succès.', Response::HTTP_CREATED);
        }
        else return new Response('Une erreur est survenue lors de l\'ajout du film.', Response::HTTP_BAD_REQUEST);
    }

    #[Route('/getall', name: 'app_getall')]
    public function getAll(ManagerRegistry $doctrine): Response
    {
       // get all movies from database and return them as json
        
        $films = $doctrine->getRepository(Film::class)->findAll();
        if (!$films) {
            throw $this->createNotFoundException(
                'No film found.'
            );
        }
        else {
            $jsonResponse = array();
            foreach($films as $film) {
                $id = $film->getId();
                $name = $film->getName();
                $synopsis = $film->getSynopsis();
                $type = $film->getType();
                $creationDate = $film->getCreationDate();
                $array = array(
                    'id' => $id,
                    'name' => $name,
                    'synopsis' => $synopsis,
                    'type' => $type,
                    'creationDate' => $creationDate
                );
                array_push($jsonResponse,$array);
                
            }
            $response = new Response(json_encode($jsonResponse,JSON_FORCE_OBJECT));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        
    }
}

    #[Route('/get/{id}')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $film = $doctrine->getRepository(Film::class)->find($id);

        if (!$film) {
            throw $this->createNotFoundException(
                'No film found for id '.$id
            );
        }
        else {
            // return selected movie Doctrine Entity as json
            $id = $film->getId();
            $name = $film->getName();
            $synopsis = $film->getSynopsis();
            $type = $film->getType();
            $creationDate = $film->getCreationDate();
            $response = new Response(json_encode([
                'id' => $id,
                'name' => $name,
                'synopsis' => $synopsis,
                'type' => $type,
                'creationDate' => $creationDate

             ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        

    }

}
