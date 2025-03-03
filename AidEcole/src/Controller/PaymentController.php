<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class PaymentController extends AbstractController{
    #[Route('/check-payment-status', name: 'check_payment_status')]
    public function checkPaymentStatus(EntityManagerInterface $entityManager): Response
    {
        // Get the logged-in user
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirect to login if the user is not authenticated
        }

        // Check the payment status
        if ($user->getPaymentStatus() === 'success') {
            // Redirect to app_annonce_new if the payment was successful
            return $this->redirectToRoute('app_annonce_new');
        } else {
            // Redirect to a different page if the payment was not successful
            return $this->redirectToRoute('app_home');
        }
    }
}
