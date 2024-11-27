<?php

namespace App\Controller;

use App\Repository\FoyerRepository;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(FoyerRepository $foyerRepository, ChambreRepository $chambreRepository): Response
    {
        $foyers = $foyerRepository->findAll();

        $chambres = $chambreRepository->findAll();

        return $this->render('utilisateur.html.twig', [
            'foyers' => $foyers,
            'chambres' => $chambres,
        ]);
    }
}


