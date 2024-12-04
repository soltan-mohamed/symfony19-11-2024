<?php

namespace App\Controller;

use App\Repository\FoyerRepository;
use App\Repository\ChambreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Foyer;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(Request $request, FoyerRepository $foyerRepository, ChambreRepository $chambreRepository): Response
    {
        $searchTerm = $request->query->get('search', '');
    
        if ($searchTerm !== '') {
            $foyers = $foyerRepository->searchByName($searchTerm);
        } else {
            $foyers = $foyerRepository->findAll();
        }
    
        $chambres = $chambreRepository->findAll();
    
        return $this->render('dashboard.html.twig', [
            'foyers' => $foyers,
            'chambres' => $chambres,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/dashboard/export/pdf', name: 'dashboard_export_pdf')]
    public function exportPdf(EntityManagerInterface $entityManager): Response
    {
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
    
        $html = $this->renderView('foyer.html.twig', [
            'foyers' => $foyers,
        ]);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("Liste_Foyers_Chambres.pdf", ["Attachment" => true]);
    
        return new Response();
    }
    
}


