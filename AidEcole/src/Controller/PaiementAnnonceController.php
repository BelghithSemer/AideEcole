<?php

namespace App\Controller;

use App\Entity\PaiementAnnonce;
use App\Form\PaiementAnnonceType;
use App\Repository\PaiementAnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/paiement/annonce')]
final class PaiementAnnonceController extends AbstractController
{
    #[Route(name: 'app_paiement_annonce_index', methods: ['GET'])]
    public function index(PaiementAnnonceRepository $paiementAnnonceRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        return $this->render('paiement_annonce/index.html.twig', [
            'paiement_annonces' => $paiementAnnonceRepository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/new', name: 'app_paiement_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $paiementAnnonce = new PaiementAnnonce();
        $form = $this->createForm(PaiementAnnonceType::class, $paiementAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiementAnnonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement_annonce/new.html.twig', [
            'paiement_annonce' => $paiementAnnonce,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_annonce_show', methods: ['GET'])]
    public function show(PaiementAnnonce $paiementAnnonce): Response
    {
        return $this->render('paiement_annonce/show.html.twig', [
            'paiement_annonce' => $paiementAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaiementAnnonce $paiementAnnonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementAnnonceType::class, $paiementAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement_annonce/edit.html.twig', [
            'paiement_annonce' => $paiementAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/supp/{id}/delete', name: 'app_paiement_annonce_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, PaiementAnnonce $paiementAnnonce = null, EntityManagerInterface $entityManager): Response
    {
        if (!$paiementAnnonce) {
            throw $this->createNotFoundException('PaiementAnnonce not found');
        }
    
        if ($this->isCsrfTokenValid('delete'.$paiementAnnonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiementAnnonce);
            $entityManager->flush();
    
            $this->addFlash('success', 'PaiementAnnonce deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }
    
        return $this->redirectToRoute('app_paiement_annonce_index', [], Response::HTTP_SEE_OTHER);
    }

    


    #[Route('/create-checkout-session', name: 'create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(Request $request): Response
    {
        // Configurez votre clé secrète Stripe
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
    
        // Créez une session de paiement Stripe
        try {
            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount' => 2000, // Montant en cents (20.00 USD)
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('paiement_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('paiement_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
    
            // Redirigez l'utilisateur vers Stripe Checkout
            return $this->redirect($checkout_session->url);
        } catch (\Exception $e) {
            // Gérez les erreurs
            $this->addFlash('error', 'An error occurred while creating the checkout session: ' . $e->getMessage());
            return $this->redirectToRoute('app_paiement_annonce_index');
        }
    }
    

    #[Route('/paiement/success', name: 'paiement_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('paiement_annonce/success.html.twig');
    }

    #[Route('/paiement/cancel', name: 'paiement_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        return $this->render('paiement_annonce/cancel.html.twig');
    }
}