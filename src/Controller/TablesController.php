<?php

namespace App\Controller;

use App\Entity\Foyer;
use App\Form\FoyerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TablesController extends AbstractController
{
    // This handles adding a new Foyer
    #[Route('/tables', name: 'app_tables')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Foyer object (for adding)
        $foyer = new Foyer();
        
        // Create the form for the new foyer
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);

        // Check if the form was submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the foyer to the database
            $entityManager->persist($foyer);
            $entityManager->flush();

            // Redirect to the same page after successful form submission
            return $this->redirectToRoute('app_tables');
        }

        // Fetch all foyers for display
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();

        // Render the template with the form and the foyers
        return $this->render('tables.html.twig', [
            'form' => $form->createView(),
            'foyers' => $foyers,  // List of foyers to display
        ]);
    }

    // This handles the editing of a specific Foyer
    #[Route('/tables/edit/{id}', name: 'app_tables_edit')]
    public function edit(Request $request, Foyer $foyer, EntityManagerInterface $entityManager): Response
    {
        // Create the form to edit the foyer
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);

        // Check if the form was submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Update the foyer in the database
            $entityManager->flush();

            // Redirect to the tables page after successful edit
            return $this->redirectToRoute('app_tables');
        }

        // Fetch all foyers for display (to be consistent with the original display)
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();

        // Render the template with the form and the foyers
        return $this->render('tables.html.twig', [
            'form' => $form->createView(),
            'foyer' => $foyer,  // Pass the specific foyer for editing
            'foyers' => $foyers,  // List of foyers for display
        ]);
    }

    // This handles the deletion of a specific Foyer
    #[Route('/tables/delete/{id}', name: 'foyer_delete', methods: ['POST'])]
    public function delete(Request $request, Foyer $foyer, EntityManagerInterface $entityManager): Response
    {
        // Verify CSRF token validity
        if ($this->isCsrfTokenValid('delete' . $foyer->getId(), $request->request->get('_token'))) {
            // Remove the foyer from the database
            $entityManager->remove($foyer);
            $entityManager->flush();

            // Redirect to the tables page after successful deletion
            return $this->redirectToRoute('app_tables');
        }

        // Redirect back to the tables page if CSRF token is invalid
        return $this->redirectToRoute('app_tables');
    }
}
