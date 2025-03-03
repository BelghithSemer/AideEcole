<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NiveauRepository;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/matiere')]
final class MatiereController extends AbstractController
{
    #[Route('/{id}', name: 'app_matiere_index', methods: ['GET'])]
    public function index(int $id, NiveauRepository $niveauRepository): Response
    {
        // Find the Niveau by ID
        $niveau = $niveauRepository->find($id);
        if (!$niveau) {
            throw $this->createNotFoundException('Niveau not found');
        }
        $user = $this->getUser();
        // Use the query builder to fetch Matieres for the given Niveau
        $matieres = $niveau->getMatieres(); // This works because of the ManyToMany relationship

        return $this->render('matiere/status_matiere.html.twig', [
            'matieres' => $matieres,
            'niveau' => $niveau,
            'user' => $user
        ]);
    }

     /* #[Route('/new', name: 'app_matiere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matiere/ajout.html.twig', [
            'matiere' => $matiere,
            'form' => $form,
        ]); 
    } */

    #[Route('/{id}', name: 'app_matiere_show', methods: ['GET'])]
    public function show(Matiere $matiere): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
            'user' => $user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_matiere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Matiere $matiere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matiere_delete', methods: ['POST'])]
    public function delete(Request $request, Matiere $matiere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($matiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
    }

}
