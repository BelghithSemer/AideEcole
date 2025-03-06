<?php

namespace App\Controller;


use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\CommentaireForum;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[Route('/forum')]
final class ForumController extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    #[Route(name: 'app_forum_index', methods: ['GET'])]
    public function index(ForumRepository $forumRepository,Security $security): Response
    {
        $user = $security->getUser();

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Forum/listeForum.html.twig', [
                'forum' => $forumRepository->findAll(),
                'user' => $user, 
            ]);
        }







        return $this->render('forum/newindex.html.twig', [
            'forums' => $forumRepository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/new', name: 'app_forum_new', methods: ['GET', 'POST'])]  
public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response  
{  
    $user = $security->getUser();  

    $forum = new Forum();  
    $form = $this->createForm(ForumType::class, $forum);  
    $form->handleRequest($request);  

    if ($form->isSubmitted()&& $form->isValid()) {  
        if ($form->isValid()) {  
            $forum->setForumUser($user); // Assignez l'utilisateur du forum  
            $forum->setDate(new \DateTime()); // Assignez la date actuelle  

            $imageFile = $form->get('Images')->getData();  
            if ($imageFile) {  
                // Gérer le fichier image  
                $newFilename = uniqid().'.'.$imageFile->guessExtension();  
                $imageFile->move(  
                    $this->getParameter('images_annonces_directory'),  
                    $newFilename  
                );  
                $forum->setImages($newFilename);  
            }  

            // Persistez l'entité  
            $entityManager->persist($forum);  
            $entityManager->flush();  

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);  
        } else {  
            $this->addFlash('error', 'Le formulaire contient des erreurs.');  
        }  
    }  
    if ($user->getRoles()[0] == 'ROLE_ADMIN') {
        return $this->render('Admin/Forum/Ajoutforum.html.twig', [
            'forum' => $forum,  
            'form' => $form->createView(),  
            'user' => $user         ]); }
    return $this->render('forum/newnew.html.twig', [  
        'forum' => $forum,  
        'form' => $form->createView(),  
        'user' => $user  
    ]);  
}

    #[Route('/{id}', name: 'app_forum_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Forum $forum): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        // Create a new comment object
        $newComment = new CommentaireForum();
        $form = $this->createFormBuilder($newComment)
            ->add('Description', TextareaType::class, [
                'label' => 'Ajouter un commentaire',
                'attr' => ['rows' => 3],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        $newComment->setUser($user); 
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newComment->setForum($forum); // Associate the comment with the forum
            // Optionally associate the comment with the user

            // Persist the new comment
            $this->entityManager->persist($newComment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès.');

            return $this->redirectToRoute('app_forum_show', ['id' => $forum->getId()]);
        }

        return $this->render('forum/newshow.html.twig', [
            'forum' => $forum,
            'user' => $user,
            'comments' => $forum->getCommentaires(), // Pass the comments to the template
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/comment/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function editcomment(Request $request, CommentaireForum $comment, EntityManagerInterface $entityManager): Response
    {
        // Ensure only the creator of the comment can edit it
        $user = $this->getUser();
        if (!$user || $user !== $comment->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }

        // Create a form to edit the comment's description
        $form = $this->createFormBuilder($comment)
            ->add('Description', TextareaType::class, [
                'label' => 'Modifier le commentaire',
                'attr' => ['rows' => 3],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the updated comment
            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a été mis à jour avec succès.');

            // Redirect back to the forum page or comment list
            return $this->redirectToRoute('app_forum_show', ['id' => $comment->getForum()->getId()]);
        }

        // Render the edit form
        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    #[Route('/comment/{id}/delete', name: 'app_comment_delete', methods: ['POST'])]  
public function deleteComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response  
{  
    if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {  
        $entityManager->remove($comment);  
        $entityManager->flush();  
    }  



    if (!$user) {
        throw $this->createAccessDeniedException('No user logged in');
    }

    if ($user->getRoles()[0] == 'ROLE_ADMIN') {
        return $this->render('Admin/Forum/listeForum.html.twig', [
            'form' => $form,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }










    return $this->redirectToRoute('app_forum_index');  
}



    #[Route('/{id}/edit', name: 'app_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 
        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/newedit.html.twig', [
            'forum' => $forum,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_forum_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
    }
}
