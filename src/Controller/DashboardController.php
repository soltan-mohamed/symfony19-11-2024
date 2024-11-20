<?php

namespace App\Controller;

use App\Repository\FoyerRepository;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(FoyerRepository $foyerRepository, ChambreRepository $chambreRepository): Response
    {
        $foyers = $foyerRepository->findAll();

        $chambres = $chambreRepository->findAll();

        return $this->render('dashboard.html.twig', [
            'foyers' => $foyers,
            'chambres' => $chambres,
        ]);
    }
}
