<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#use App\Service\MailerService;
use Symfony\Component\Notifier\Recipient\Recipient;
use App\Notification\ReclamationResolvedNotification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\NotifierInterface;
use App\Service\MailerService;



use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Mime\Email;
#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{
    #[Route('/list' , name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/reclamations/rec.html.twig', [
                'reclamations' => $reclamationRepository->findAll(),
                'user' => $user, 
            ]);}

        return $this->render('reclamation/student-reviews.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'user' => $user
        ]);
    }
    

    #[Route(name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerService $mailerService): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $user = $this->getUser();
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez setReclamationId() au lieu de setUser()
            $reclamation->setReclamationId($user);
    
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            // Envoyez un email de confirmation
            $mailerService->sendConfirmationEmail($reclamation);    
            $this->addFlash('success', 'Réclamation créée et email de confirmation envoyé.');
    
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reclamation/admin-profile.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'user' => $user,
        ]);
    }
    
    #[Route('/{id}/resolve', name: 'app_reclamation_resolve', methods: ['POST'])]
    public function resolve(Reclamation $reclamation, EntityManagerInterface $entityManager, NotifierInterface $notifier): Response
    {
        // Set the reclamation status to resolved
        $reclamation->setStatut('resolu');
        $entityManager->flush();

        // Debug: Check the reclamation data
        dump($reclamation->getSujet(), $reclamation->getDescription());

        // Send the notification
        $recipientEmail = $reclamation->getEmail(); // Replace with the recipient's email

        $notification = (new Notification('Reclamation Confirmé ', ['email']))
            ->content('Votre Reclamation a ete confirme par l admin ');

        $recipient = new Recipient($recipientEmail);

        $notifier->send($notification, $recipient);

        $this->addFlash('success', 'Réclamation résolue et e-mail de résolution envoyé !');
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/test-email', name: 'test_email')]
    public function sendTestEmail(NotifierInterface $notifier): Response
    {
        $recipientEmail = 'samerbelghith2017@gmail.com'; // Replace with the recipient's email

        $notification = (new Notification('Test Email', ['email']))
            ->content('This is a test email.');

        $recipient = new Recipient($recipientEmail);

        $notifier->send($notification, $recipient);

        return new Response('Test email sent!');
    }



    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST' , 'GET'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
       
    
        $entityManager->remove($reclamation);
        $entityManager->flush();
    
        $this->addFlash('success', 'Reclamation deleted successfully.');
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
