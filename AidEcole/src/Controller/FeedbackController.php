<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Form\FeedbackParentType;
use App\Form\FeedbackDonnateurType;
use App\Form\FeedbackCentreType;
use App\Form\FeedbackResponsableType;
use App\Form\FeedbackEnseignantType;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/feedback')]
final class FeedbackController extends AbstractController
{
    #[Route('/list' , name: 'app_feedback_index', methods: ['GET'])]
    public function index(FeedbackRepository $feedbackRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/feedback/feed.html.twig', [
                'feedbacks' => $feedbackRepository->findAll(),
                'user' => $user, 
            ]);}

        return $this->render('feedback/student-reviews.html.twig', [
            'feedbacks' => $feedbackRepository->findAll(),
            'user' => $user
        ]);
    }
    

    #[Route('/new' , name: 'app_feedback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $feedback = new Feedback();
        

        $user = $security->getUser();
        if( $user && in_array('ROLE_PARENT', $user->getRoles()) ){
            $form = $this->createForm(FeedbackParentType::class, $feedback);
            $form->handleRequest($request);
        }elseif(
            $user && in_array('ROLE_DONATEUR', $user->getRoles()) ){
                $form = $this->createForm(FeedbackDonnateurType::class, $feedback);
                $form->handleRequest($request);

            }
            
            
            elseif($user && in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles()) ){
                $form = $this->createForm(FeedbackResponsableType::class, $feedback);
                $form->handleRequest($request);
        }
        elseif($user && in_array('ROLE_ENSEIGNANT', $user->getRoles()) ){
            $form = $this->createForm(FeedbackEnseignantType::class, $feedback);
            $form->handleRequest($request);
        }

        else {
            $form = $this->createForm(FeedbackCentreType::class, $feedback);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($feedback);
            $entityManager->flush();

            return $this->redirectToRoute('app_feedback_index', [], Response::HTTP_SEE_OTHER);
        }else{
            dump($form->getErrors(true, false)); 
        }
       

        return $this->render('feedback/admin-profile.html.twig', [
            'user'=> $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_feedback_show', methods: ['GET', 'POST'])]  
    public function show(int $id, Request $request, Security $security, EntityManagerInterface $entityManager): Response  
    {  
        // Fetch the feedback entity by ID  
        $feedback = $entityManager->getRepository(Feedback::class)->find($id);  
    
        if (!$feedback) {  
            throw $this->createNotFoundException('Feedback not found.');  
        }  
    
        $user = $security->getUser();  
        $form = null;  
    
        // Create different forms based on user roles  
        if ($user && in_array('ROLE_PARENT', $user->getRoles())) {  
            $form = $this->createForm(FeedbackParentType::class, $feedback);  
        } elseif ($user && in_array('ROLE_DONATEUR', $user->getRoles())) {  
            $form = $this->createForm(FeedbackDonnateurType::class, $feedback);  
        } elseif ($user && in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles())) {  
            $form = $this->createForm(FeedbackResponsableType::class, $feedback);  
        } elseif ($user && in_array('ROLE_ENSEIGNANT', $user->getRoles())) {  
            $form = $this->createForm(FeedbackEnseignantType::class, $feedback);  
        } else {  
            $form = $this->createForm(FeedbackCentreType::class, $feedback);  
        }  
    
        $form->handleRequest($request);  
    
        if ($form->isSubmitted() && $form->isValid()) {  
            $entityManager->persist($feedback);  
            $entityManager->flush();  
    
            return $this->redirectToRoute('app_feedback_index', [], Response::HTTP_SEE_OTHER);  
        } else {  
            // Dump form errors for debugging  
            dump($form->getErrors(true, false));   
        }  
    
        // Render the template with the form  
        return $this->render('feedback/show.html.twig', [  
            'feedback' => $feedback,  
            'form' => $form->createView(), // Pass the form to the template  
            'user' => $user,  
        ]);  
    }
    
    #[Route('/{id}/edit', name: 'app_feedback_edit', methods: ['GET', 'POST'])]  
public function edit(Request $request, Feedback $feedback, EntityManagerInterface $entityManager, Security $security): Response  
{  
    $user = $security->getUser();  

    // Determine which form to create based on user roles  
    if ($user && in_array('ROLE_PARENT', $user->getRoles())) {  
        $form = $this->createForm(FeedbackParentType::class, $feedback);  
    } elseif ($user && in_array('ROLE_DONATEUR', $user->getRoles())) {  
        $form = $this->createForm(FeedbackDonnateurType::class, $feedback);  
    } elseif ($user && in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles())) {  
        $form = $this->createForm(FeedbackResponsableType::class, $feedback);  
    } elseif ($user && in_array('ROLE_ENSEIGNANT', $user->getRoles())) {  
        $form = $this->createForm(FeedbackEnseignantType::class, $feedback);  
    } else {  
        // Default case for other roles  
        $form = $this->createForm(FeedbackCentreType::class, $feedback);  
    }  

    // Handle the request for the generated form  
    $form->handleRequest($request);  

    if ($form->isSubmitted() && $form->isValid()) {  
        $entityManager->flush(); // Persist updates  

        return $this->redirectToRoute('app_feedback_index', [], Response::HTTP_SEE_OTHER);  
    } else {  
        dump($form->getErrors(true, false)); // Debug form errors  
    }  

    // Render the template with feedback and form  
    return $this->render('feedback/edit.html.twig', [  
        'feedback' => $feedback,  
        'form' => $form->createView(),
         'user'=> $user,  
    ]);  
}
    #[Route('/{id}', name: 'app_feedback_delete', methods: ['POST' , 'GET'])]
    public function delete(Request $request, Feedback $feedback, EntityManagerInterface $entityManager): Response
    {
        
            $entityManager->remove($feedback);
            $entityManager->flush();

            $this->addFlash('success', 'Feedback deleted successfully.');

    
        return $this->redirectToRoute('app_feedback_index', [], Response::HTTP_SEE_OTHER);
    }
    

}
