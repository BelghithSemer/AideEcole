<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;  // Correct namespace
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
   public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    
    #[Route('/dashbord', name: 'app_dashbord')]
    public function profile(): Response
    {
        $user = $this->getUser(); 

    if (!$user) {
        throw $this->createAccessDeniedException('No user logged in');
    }
    return $this->render('home/quiz.html.twig', [
        'user' => $user,
    ]);
    
    } 
}
