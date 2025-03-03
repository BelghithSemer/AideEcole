<?php

namespace App\Controller;
use App\Entity\Commentaire;
use App\Entity\CommentaireForum;
use App\Form\CommentaireForumType;
use App\Repository\CommentaireForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/commentaire/forum')]
final class CommentaireForumController extends AbstractController
{
    #[Route(name: 'app_commentaire_forum_index', methods: ['GET'])]
    public function index(CommentaireForumRepository $commentaireForumRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Commentaire/listeCommentaire.html.twig', [
                'commentaire_forums' => $commentaireForumRepository->findAll(),
                'user' => $user, 
            ]);
        }

        return $this->render('commentaire_forum/index.html.twig', [
            'commentaire_forums' => $commentaireForumRepository->findAll(),
            'user' => $user,

        ]);
    }

    #[Route('/new', name: 'app_commentaire_forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        $commentaireForum = new CommentaireForum();
        $form = $this->createForm(CommentaireForumType::class, $commentaireForum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaireForum);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire_forum/new.html.twig', [
            'commentaire_forum' => $commentaireForum,
            'form' => $form,
            'user' => $user

        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_forum_show', methods: ['GET'])]
    public function show(CommentaireForum $commentaireForum): Response
    {
        return $this->render('commentaire_forum/show.html.twig', [
            'commentaire_forum' => $commentaireForum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommentaireForum $commentaireForum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireForumType::class, $commentaireForum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire_forum/edit.html.twig', [
            'commentaire_forum' => $commentaireForum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_forum_delete', methods: ['POST'])]
    public function delete(Request $request, CommentaireForum $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
    }
}
