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

class TestController extends AbstractController
{

    #[Route('/test', name: 'app_test')]
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
    
        return $this->render('billing.html.twig', [
            'chambres' => $chambres,
            'form' => $form->createView(),
            'searchTerm' => $searchTerm, // Passer le terme de recherche au template
        ]);
    }

    #[Route('/test/edit/{id}', name: 'app_test_edit')]
    public function edit(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        $chambres = $entityManager->getRepository(Chambre::class)->findAll();
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
        $form = $this->createForm(ChambreType::class, $chambre, [
            'foyers' => $foyers,
        ]);
        $form->handleRequest($request);
        return $this->redirectToRoute('app_create_chambre');


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->render('billing.html.twig', [
                'chambres' => $chambres,
                'form' => $form->createView(),

            ]);

        }

        $chambres = $entityManager->getRepository(Chambre::class)->findAll();
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();

        return $this->render('billing.html.twig', [
            'form' => $form->createView(),
            'foyers' => $foyers,
            'chambre' => $chambre,
            'chambres' => $chambres,
        ]);
    }

    #[Route('/test/{id}/delete', name: 'test_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_test');
    }
}
