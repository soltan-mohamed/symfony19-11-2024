<?php

namespace App\Controller;

use App\Entity\Foyer;
use App\Form\FoyerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


class CreateFoyerController extends AbstractController
{
    #[Route('/create/foyer', name: 'app_create_foyer')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $foyer = new Foyer();
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($foyer);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_tables');
        }
    
        $searchTerm = $request->query->get('search', ''); // Récupère la valeur du champ de recherche
        $foyers = $searchTerm 
            ? $entityManager->getRepository(Foyer::class)->searchByName($searchTerm) 
            : $entityManager->getRepository(Foyer::class)->findAll();
    
        return $this->render('ajoutfoyer.html.twig', [
            'form' => $form->createView(),
            'foyers' => $foyers,
            'searchTerm' => $searchTerm, // Pour réafficher la recherche
        ]);
    }
}




