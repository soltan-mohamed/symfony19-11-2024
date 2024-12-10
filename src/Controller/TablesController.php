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
        $foyer = new Foyer();
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($foyer);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_tables');
        }
    
        $searchTerm = $request->query->get('search', '');
        $foyers = $searchTerm 
            ? $entityManager->getRepository(Foyer::class)->searchByName($searchTerm) 
            : $entityManager->getRepository(Foyer::class)->findAll();
    
        return $this->render('tables.html.twig', [
            'form' => $form->createView(),
            'foyers' => $foyers,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/tables/edit/{id}', name: 'app_tables_edit')]
    public function edit(Request $request, Foyer $foyer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('modifier', 'Le foyer a été modifié avec succès !');


            return $this->redirectToRoute('app_create_foyer');
        }
        $foyers = $entityManager->getRepository(Foyer::class)->findAll();

        return $this->render('ajoutfoyer.html.twig', [
            'form' => $form->createView(),
            'foyer' => $foyer,
            'foyers' => $foyers,
        ]);

    }

    #[Route('/tables/delete/{id}', name: 'foyer_delete', methods: ['POST'])]
    public function delete(Request $request, Foyer $foyer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $foyer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($foyer);
            $entityManager->flush();

            return $this->redirectToRoute('app_tables');
        }

        return $this->redirectToRoute('app_tables');
    }
}
