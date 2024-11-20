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
        $chambres = $chambreRepository->findAll();
    
        // Create a new Chambre and its form
        $chambre = new Chambre();
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
        $form = $this->createForm(ChambreType::class, $chambre, [
            'foyers' => $foyers,

        ]);
        $form->handleRequest($request);
    
        // If the form is submitted and valid, handle saving the Chambre
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chambre);
            $entityManager->flush();
            return $this->redirectToRoute('app_test');
        }
    
        return $this->render('billing.html.twig', [
            'chambres' => $chambres,
            'form' => $form->createView(),
        ]);
    }

    // Edit an existing chambre
    #[Route('/test/edit/{id}', name: 'app_test_edit')]
    public function edit(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        // Create the form to edit the foyer
        $chambres = $entityManager->getRepository(Chambre::class)->findAll();

        $foyers = $entityManager->getRepository(Foyer::class)->findAll();
        $form = $this->createForm(ChambreType::class, $chambre, [
            'foyers' => $foyers,
        ]);
        $form->handleRequest($request);

        // Check if the form was submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the foyer in the database
            $entityManager->flush();

            // Redirect to the tables page after successful edit
            return $this->render('billing.html.twig', [
                'chambres' => $chambres,
                'form' => $form->createView(),

            ]);

        }

        // Fetch all foyers for display (to be consistent with the original display)
        $chambres = $entityManager->getRepository(Chambre::class)->findAll();
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();

        // Render the template with the form and the foyers
        return $this->render('billing.html.twig', [
            'form' => $form->createView(),
            'foyers' => $foyers,  // Pass the specific foyer for editing

            'chambre' => $chambre,  // Pass the specific foyer for editing
            'chambres' => $chambres,  // List of foyers for display
        ]);
    }

    // Delete a chambre
    #[Route('/test/{id}/delete', name: 'test_delete', methods: ['POST'])]
    public function delete(Request $request, Chambre $chambre, EntityManagerInterface $entityManager): Response
    {
        // Validate the CSRF token
        if ($this->isCsrfTokenValid('delete'.$chambre->getId(), $request->request->get('_token'))) {
            // Remove the chambre from the database
            $entityManager->remove($chambre);
            $entityManager->flush();
        }

        // Redirect back to the billing page after deletion
        return $this->redirectToRoute('app_test');
    }
}
