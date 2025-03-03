<?php

namespace App\Controller;

use App\Entity\Bons;
use App\Form\BonsType;
use App\Repository\BonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bons')]
final class BonsController extends AbstractController
{
    #[Route(name: 'app_bons_index', methods: ['GET'])]
    public function index(BonsRepository $bonsRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        return $this->render('bons/lIst.html.twig', [
            'bons' => $bonsRepository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/new', name: 'app_bons_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bon = new Bons();
        $form = $this->createForm(BonsType::class, $bon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bon);
            $entityManager->flush();

            return $this->redirectToRoute('app_bons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bons/new.html.twig', [
            'bon' => $bon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bons_show', methods: ['GET'])]
    public function show(Bons $bon): Response
    {
        return $this->render('bons/show.html.twig', [
            'bon' => $bon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bons_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bons $bon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BonsType::class, $bon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bons/edit.html.twig', [
            'bon' => $bon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bons_delete', methods: ['POST'])]
    public function delete(Request $request, Bons $bon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bons_index', [], Response::HTTP_SEE_OTHER);
    }
}
