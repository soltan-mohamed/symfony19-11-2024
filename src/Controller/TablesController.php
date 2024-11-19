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
    #[Route('/tables', name: 'app_tables')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new Foyer object
        $foyer = new Foyer();

        // Create the form using FoyerType
        $form = $this->createForm(FoyerType::class, $foyer);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the Foyer entity and save it to the database
            $entityManager->persist($foyer);
            $entityManager->flush();

            // Redirect after successful submission (or to any other page)
            return $this->redirectToRoute('app_tables');
        }

    // Fetch all foyers from the database
    $foyers = $entityManager->getRepository(Foyer::class)->findAll();


        // Render the template with the form and the foyer object
        return $this->render('tables.html.twig', [
            'form' => $form->createView(),  // Pass the form to the template
            'foyers' => $foyers,    // Pass the form to the template
        ]);
    }

    #[Route('/tables/{id}', name: 'app_tables_show', methods: ['GET'])]
    public function show(Foyer $foyer): Response
    {
        // Fetch associated chambres for the given foyer
        $chambres = $foyer->getChambres();

        // Pass the foyer and its chambres to the template
        return $this->render('tables.html.twig', [
            'foyer' => $foyer,
            'chambres' => $chambres,
        ]);
    }
}
