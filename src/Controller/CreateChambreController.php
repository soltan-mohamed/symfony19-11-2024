<?php

namespace App\Controller;

use App\Entity\Foyer;

use App\Entity\Chambre;
use App\Form\ChambreType;
use App\Form\FoyerType;

use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateChambreController extends AbstractController
{
    #[Route('/create/chambre', name: 'app_create_chambre')]
    public function index(Request $request, ChambreRepository $chambreRepository, EntityManagerInterface $entityManager): Response
    {
        $searchTerm = $request->query->get('search'); // Récupérer le terme de recherche
    
        // Utiliser la méthode du repository pour effectuer la recherche
        $chambres = $chambreRepository->searchByNumero($searchTerm);
    
        $chambre = new Chambre();
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
        $form = $this->createForm(ChambreType::class, $chambre, [
            'foyers' => $foyers,
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chambre);
            $entityManager->flush();
            return $this->redirectToRoute('app_test');
        }
    
        return $this->render('ajoutchambre.html.twig', [
            'chambres' => $chambres,
            'form' => $form->createView(),
            'searchTerm' => $searchTerm, // Passer le terme de recherche au template
        ]);
    }
}
