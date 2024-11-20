<?php

namespace App\Controller;

use App\Entity\Foyer;
use App\Entity\Chambre;

use App\Form\FoyerType;
use App\Repository\FoyerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FoyerController extends AbstractController
{
    #[Route('/foyer', name: 'foyer_index')]

    public function index(FoyerRepository $foyerRepository): Response
    {
        return $this->render('foyer/index.html.twig', [
            'foyers' => $foyerRepository->findAll(),
        ]);
    }

    #[Route('/foyer/new', name: 'foyer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $foyer = new Foyer();
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($foyer);
            $entityManager->flush();

            return $this->render('foyer/new.html.twig', [
                'foyer' => $foyer,
                'form' => $form, 
            ]);        
        }

        return $this->renderForm('foyer/new.html.twig', [
            'foyer' => $foyer,
            'form' => $form,
        ]);
    }

    #[Route('/foyer/{id}', name: 'foyer_show', methods: ['GET'])]
    public function show(Foyer $foyer): Response
    {
        $chambres = $foyer->getChambres();
    
        foreach ($chambres as $chambre) {
            $numero = $chambre->getNumero();
            $capacite = $chambre->getCapacite();
 
        }
    
        return $this->render('foyer/show.html.twig', [
            'foyer' => $foyer,
            'chambres' => $chambres, 
        ]);
    }

    #[Route('/foyer/{id}/edit', name: 'foyer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Foyer $foyer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FoyerType::class, $foyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('foyer_index');
        }

        return $this->renderForm('foyer/edit.html.twig', [
            'foyer' => $foyer,
            'form' => $form,
        ]);
    }

    #[Route('/foyer/{id}', name: 'foyer_delete', methods: ['POST'])]
    public function delete(Request $request, Foyer $foyer, EntityManagerInterface $entityManager): Response
    {


        $csrfToken = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete' . $foyer->getId(), $csrfToken)) {
            $entityManager->remove($foyer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('foyer_index');
    }
}
