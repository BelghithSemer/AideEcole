<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CommentaireRepository;
use App\Service\NotificationService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Bundle\SecurityBundle\Security;
#[Route('/annonce')]
final class AnnonceController extends AbstractController
{
    #[Route(name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Annonce/listeAnnonce.html.twig', [
                'annonces' => $annonceRepository->findAll(),
                'user' => $user, 
            ]);
        }



        return $this->render('annonce/Listedannoncescentre.html.twig', [
            'annonces' => $annonceRepository->findAll(),
            'user' => $user
        ]);
    }


    #[Route('/list',name: 'app_annonceadmin_index', methods: ['GET'])]
    public function indexadmin(AnnonceRepository $annonceRepository): Response
    {

        $user = $this->getUser();
        return $this->render('annonce/Listedannoncescentre.html.twig', [
            'annonces' => $annonceRepository->findAll(),
            'user' => $user
        ]);
    }

    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }



    // src/Controller/AnnonceController.php
    #[Route('/front', name: 'app_annonceparent_index_1', methods: ['GET', 'POST'])]
    public function indexparent(
            AnnonceRepository $annonceRepository,
            Request $request,
            EntityManagerInterface $entityManager,
            Security $security,
            CommentaireRepository $commentaireRepository,
            NotificationService $notificationService
        ): Response {
            $annonces = $annonceRepository->findAll();
            $user = $security->getUser();
        
            if (!$user) {
                throw $this->createAccessDeniedException('No user logged in');
            }
        
            // Récupérer les notifications de l'utilisateur connecté
            $notifications = $user->getNotifications();
        
            if ($request->isMethod('POST')) {
                $annonceId = $request->request->get('annonce_id');
                $annonce = $annonceRepository->find($annonceId);
        
                if (!$annonce) {
                    throw $this->createNotFoundException('L\'annonce spécifiée n\'existe pas.');
                }
        
                $commentaire = new Commentaire();
                $form = $this->createForm(CommentaireType::class, $commentaire);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    $currentUser = $security->getUser();
        
                    $commentaire->setDate(new \DateTimeImmutable());
                    $commentaire->setCommentaireAnnonce($annonce);
                    $commentaire->setUser($currentUser);
        
                    $entityManager->persist($commentaire);
                    $entityManager->flush();
        
                    // Vérifier si la collection $CentreFormationId n'est pas vide
                    if ($annonce->getCentreFormationId()->isEmpty()) {
                        $this->addFlash('warning', 'Aucun utilisateur associé à cette annonce. La notification n\'a pas été envoyée.');
                    } else {
                        // Récupérer un seul utilisateur à partir de la collection
                        $annonceAuthor = $annonce->getCentreFormationId()->first();
                        if (!$annonceAuthor instanceof User) {
                            throw new \RuntimeException('Aucun utilisateur trouvé pour cette annonce.');
                        }
        
                        // Envoyer une notification à l'auteur de l'annonce
                        $subject = 'Nouveau commentaire sur votre annonce';
                        $message = sprintf(
                            'Un nouveau commentaire a été ajouté à votre annonce "%s".',
                            $annonce->getTitre()
                        );
        
                        $this->notificationService->sendNotification($annonceAuthor, $subject, $message);
                    }
        
                    return $this->redirectToRoute('app_annonceparent_index_1');
                }
            }
        
            $forms = [];
            foreach ($annonces as $annonce) {
                $forms[$annonce->getId()] = $this->createForm(CommentaireType::class)->createView();
            }
        
            if ($user->getRoles()[0] == 'ROLE_ADMIN') {
                return $this->render('Admin/commentaire/cmnt.html.twig', [
                    'commentaire' => $commentaireRepository->findAll(),
                ]);
            }
        
            return $this->render('annonce/blog-dark.html.twig', [
                'annonces' => $annonces,
                'forms' => $forms,
                'user' => $user,
                'notifications' => $notifications, // Passer les notifications au template
            ]);
        }





    #[Route('/student-settings', name: 'app_student_settings', methods: ['GET'])]
    public function studentSettings(): Response
    {
        $user = $this->getUser();
    
        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
    
        return $this->render('annonce/student-settings.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/comment/delete/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
public function deleteComment(Request $request, EntityManagerInterface $entityManager, Commentaire $commentaire): Response
{
    try {
        // Ensure the current user is authorized to delete the comment
        $currentUser = $this->getUser();
        if (!$currentUser || $commentaire->getUser() !== $currentUser) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }

        // Remove the comment from the database
        $entityManager->remove($commentaire);
        $entityManager->flush();

        // Return a JSON response for AJAX requests
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => true]);
        }

        // Redirect back to the announcement page for non-AJAX requests
        return $this->redirectToRoute('app_annonceparent_index_1');
    } catch (\Exception $e) {
        // Log the error for debugging purposes
        $this->addFlash('error', 'Une erreur est survenue lors de la suppression du commentaire.');
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
        return $this->redirectToRoute('app_annonceparent_index_1');
    }
}

    #[Route('/comment/update/{id}', name: 'app_commentaire_update', methods: ['GET', 'POST'])]
public function updateComment(Request $request, EntityManagerInterface $entityManager, Commentaire $commentaire): Response
{
    // Ensure the current user is authorized to update the comment
    $currentUser = $this->getUser();
    if (!$currentUser || $commentaire->getUser() !== $currentUser) {
        $this->addFlash('error', "Vous n'avez pas accès pour modifier !");
        //alert("Vous n'avez pas acces pour modifier ! ");
        //throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce commentaire.');
    }

    // Create the form for updating the comment
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the updated comment
        $entityManager->persist($commentaire);
        $entityManager->flush();

        // Redirect back to the announcement page
        return $this->redirectToRoute('app_annonceparent_index_1');
    }

    // Pass both the form and the commentaire object to the template
    return $this->render('annonce/update.html.twig', [
        'form' => $form->createView(),
        'commentaire' => $commentaire, // Pass the commentaire object
    ]);
}

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('Image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_annonces_directory'),
                    $newFilename
                );
                $annonce->setImage($newFilename);
            }

            $entityManager->persist($annonce);
            $entityManager->flush();

            if ($user->getRoles()[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);

            }
            return $this->redirectToRoute('app_paiement_annonce_new', [], Response::HTTP_SEE_OTHER);
        }



        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Annonce/listeAjoutAnnonce.html.twig', [
                'annonce' => $annonce,
                'form' => $form->createView(),
                'user' => $user
            ]);
        }
        return $this->render('annonce/Ajout.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('Image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_annonces_directory'),
                    $newFilename
                );
                $annonce->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }


        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Annonce/listeModifieAnnonce.html.twig', [
                'user' => $user, 
                'annonce' => $annonce,
            'form' => $form->createView(),
            ]);



        }

        
        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'user' => $user


        ]);
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/comment/{id}/like', name: 'comment_like', methods: ['POST'])]
    public function likeComment(Commentaire $commentaire, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Récupérer l'utilisateur actuel
        $user = $security->getUser();
    
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour liker un commentaire.');
        }
    
        // Incrémenter les likes pour l'utilisateur actuel
        $commentaire->incrementLikes($user);
        $entityManager->flush();
    
        $this->addFlash('success', 'Commentaire liké !');
        return $this->redirectToRoute('app_annonceparent_index_1');


    }


    #[Route('/comment/{id}/unlike', name: 'comment_unlike', methods: ['POST'])]
public function unlikeComment(Commentaire $commentaire, EntityManagerInterface $entityManager, Security $security): Response
{
    // Récupérer l'utilisateur actuel
    $user = $security->getUser();

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour retirer votre like.');
    }

    // Décrémenter les likes pour l'utilisateur actuel
    $commentaire->decrementLikes($user);
    $entityManager->flush();

    $this->addFlash('success', 'Like retiré !');
    return $this->redirectToRoute('app_annonceparent_index_1');
}

    
    #[Route('/comment/{id}/dislike', name: 'comment_dislike', methods: ['POST'])]
    public function dislikeComment(Commentaire $commentaire, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Récupérer l'utilisateur actuel
        $user = $security->getUser();
    
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour disliker un commentaire.');
        }
    
        // Incrémenter les dislikes pour l'utilisateur actuel
        $commentaire->incrementDislikes($user);
        $entityManager->flush();
    
        $this->addFlash('success', 'Commentaire disliké !');
        return $this->redirectToRoute('app_annonceparent_index_1');

    }

    #[Route('/comment/{id}/undislike', name: 'comment_undislike', methods: ['POST'])]
    public function undislikeComment(Commentaire $commentaire, EntityManagerInterface $entityManager, Security $security): Response
    {

        // Récupérer l'utilisateur actuel
    $user = $security->getUser();

    // Vérifier si l'utilisateur est connecté
    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour retirer votre dislike.');
    }
        // Décrémenter les dislikes pour l'utilisateur actuel
    $commentaire->decrementDislikes($user);
    $entityManager->flush();

    $this->addFlash('success', 'Dislike retiré !');
    return $this->redirectToRoute('app_annonceparent_index_1');
    }

}