<?php

namespace App\Controller;

use App\Repository\FoyerRepository;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(Request $request, FoyerRepository $foyerRepository, ChambreRepository $chambreRepository): Response
    {
        // Récupérer le terme de recherche à partir de la requête GET
        $searchTerm = $request->query->get('search', '');
    
        // Rechercher les foyers en fonction du terme, ou afficher tous les foyers si aucun terme n'est fourni
        if ($searchTerm !== '') {
            $foyers = $foyerRepository->searchByName($searchTerm);
        } else {
            $foyers = $foyerRepository->findAll();
        }
    
        // Récupérer toutes les chambres (facultatif)
        $chambres = $chambreRepository->findAll();
    
        return $this->render('dashboard.html.twig', [
            'foyers' => $foyers,
            'chambres' => $chambres,
            'searchTerm' => $searchTerm, // Pour préremplir le champ de recherche
        ]);
    }
}


