<?php
namespace App\Controller;

use App\Entity\Foyer;
use App\Entity\DemandeSelection;
use App\Repository\FoyerRepository;
use App\Repository\ChambreRepository;
use App\Repository\DemandeSelectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Asset\Packages;




class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        Request $request,
        FoyerRepository $foyerRepository,
        ChambreRepository $chambreRepository,
        DemandeSelectionRepository $demandeSelectionRepository
    ): Response {
        $searchTerm = $request->query->get('search', '');

        if ($searchTerm !== '') {
            $foyers = $foyerRepository->searchByName($searchTerm);
        } else {
            $foyers = $foyerRepository->findAll();
        }

        $chambres = $chambreRepository->findAll();
        $demandes = $demandeSelectionRepository->findBy(['statut' => 'En attente']); // Filtrer les demandes en attente

        return $this->render('dashboard.html.twig', [
            'foyers' => $foyers,
            'chambres' => $chambres,
            'demandes' => $demandes,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/dashboard/export/pdf', name: 'dashboard_export_pdf')]
    public function exportPdf(EntityManagerInterface $entityManager): Response
    {
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
        
        $imagePath = 'http://127.0.0.1:8000/assets/img/esprit.jpg';
        
        $html = $this->renderView('foyer.html.twig', [
            'foyers' => $foyers,
            'imagePath' => $imagePath,
        ]);
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $dompdf->stream("Liste_Foyers_Chambres.pdf", ["Attachment" => true]);
    
        return new Response();
    }
    
    
    
    #[Route('/dashboard/export/excel', name: 'dashboard_export_excel')]
    public function exportExcel(EntityManagerInterface $entityManager): Response
    {
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('A1', 'Foyer');
        $sheet->setCellValue('B1', 'Numéro de la Chambre');
        $sheet->setCellValue('C1', 'Capacité de la chambre');
        $sheet->setCellValue('D1', 'Etat de la chambre');
        $sheet->setCellValue('E1', 'Type de lit dans la chambre');
    
        $row = 2;
        foreach ($foyers as $foyer) {
            foreach ($foyer->getChambres() as $chambre) {
                $sheet->setCellValue('A' . $row, $foyer->getNom());
                $sheet->setCellValue('B' . $row, $chambre->getNumero());
                $sheet->setCellValue('C' . $row, $chambre->getCapacite());
                $sheet->setCellValue('D' . $row, $chambre->getEtat());
                $sheet->setCellValue('E' . $row, $chambre->getTypeLit());
                $row++;
            }
        }
    
        $writer = new Xlsx($spreadsheet);
        $filePath = __DIR__ . '/../../public/foyers.xlsx';
        $writer->save($filePath);
    
        return new Response('Fichier Excel créé avec succès à : ' . $filePath);
    }
    
    
    

    #[Route('/dashboard/demande/{id}/accepter', name: 'dashboard_accepter_demande', methods: ['POST'])]
    public function accepterDemande(
        int $id,
        DemandeSelectionRepository $demandeSelectionRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $demande = $demandeSelectionRepository->find($id);
    
        if (!$demande) {
            $this->addFlash('error', 'Demande non trouvée.');
            return $this->redirectToRoute('dashboard');
        }
    
        if ($demande->getStatut() !== 'accepted') {
            $demande->setStatut('accepted'); // Mettre à jour le statut
            $entityManager->flush();
            $this->addFlash('ok', 'Demande acceptée.');
        }
    
        return $this->redirectToRoute('dashboard');
    }
    


    #[Route('/dashboard/demande/{id}/supprimer', name: 'dashboard_supprimer_demande', methods: ['POST'])]
    public function supprimerDemande(
        int $id,
        DemandeSelectionRepository $demandeSelectionRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $demande = $demandeSelectionRepository->find($id);
    
        if (!$demande) {
            $this->addFlash('error', 'Demande non trouvée.');
            return $this->redirectToRoute('dashboard');
        }
    
        $entityManager->remove($demande);
        $entityManager->flush();
    
        $this->addFlash('successss', 'Demande supprimée avec succès.');
        return $this->redirectToRoute('dashboard');
    }
}
