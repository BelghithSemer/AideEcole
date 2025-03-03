<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

final class WebhookController extends AbstractController{


    #[Route('/webhook/stripe', name: 'app_webhook')]
    public function index(LoggerInterface $logger, EntityManagerInterface $entityManager): Response
    {
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_sk'));
        $event = null;

        // Check request
        $endpoint_secret = $this->getParameter('stripe_webhook_secret');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            $logger->info('Webhook Stripe Invalid payload');
            return new Response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            $logger->info('Webhook Stripe Invalid signature');
            return new Response('Invalid signature', 403);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $logger->info('Webhook Stripe checkout.session.completed');
                $session = $event->data->object;

                // Retrieve the customer email from the session
                $customerEmail = $session->customer_details->email;

                // Find the user in your database
                $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $customerEmail]);
                if (!$user) {
                    $logger->info('Webhook Stripe user not found');
                    return new Response('User not found', 404);
                }

                // Calculate the new end date for the subscription
                $paymentDate = new \DateTime(); // Current date and time
                $endDateSub = clone $paymentDate;
                $endDateSub->modify('+1 year'); // Add 1 year to the current date

                // Update the user's endDateSub
                $user->setEndDateSub($endDateSub);

                // Mark the payment as successful
                $user->setPaymentStatus('success'); 
                // Persist the changes
                $entityManager->persist($user);
                $entityManager->flush();

                $logger->info('Updated endDateSub for user: ' . $user->getEmail());
                break;

            default:
                // Unexpected event type
                $logger->info('Webhook Stripe unexpected event type: ' . $event->type);
                return new Response('Unexpected event type', 400);
        }


        //return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        return new Response('Success', 200);
    }


   
}
