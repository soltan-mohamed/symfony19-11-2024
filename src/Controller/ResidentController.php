<?php

namespace App\Controller;

use App\Entity\Resident;
use App\Form\ResidentType;
use App\Repository\ResidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/resident', name: 'app_resident')]
class ResidentController extends AbstractController
{
    #[Route('/', name: 'resident_index', methods: ['GET'])]
    public function index(ResidentRepository $residentRepository): Response
    {
        return $this->render('resident/index.html.twig', [
            'residents' => $residentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'resident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $resident = new Resident();
        $form = $this->createForm(ResidentType::class, $resident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resident);
            $entityManager->flush();

            return $this->redirectToRoute('resident_index');
        }

        return $this->renderForm('resident/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'resident_show', methods: ['GET'])]
    public function show(Resident $resident): Response
    {
        return $this->render('resident/show.html.twig', [
            'resident' => $resident,
        ]);
    }

    #[Route('/{id}/edit', name: 'resident_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resident $resident, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResidentType::class, $resident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('resident_index');
        }

        return $this->renderForm('resident/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'resident_delete', methods: ['POST'])]
    public function delete(Request $request, Resident $resident, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resident->getId(), $request->request->get('_token'))) {
            $entityManager->remove($resident);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resident_index');
    }
}
