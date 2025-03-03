<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(
        Request $request, 
        EntityManagerInterface $em, 
        AuthenticationUtils $authenticationUtils,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        
    
        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');
    
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
    
            if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
                $error = 'Identifiants invalides'; 
                return $this->render('security/login.html.twig', [
                    'error' => $error,
                    'last_username' => $email
                ]);
            }
    
            if ($this->getUser()) {
                $user = $this->getUser();
                $roles = $user->getRoles(); 
            
                if (in_array('ROLE_ADMIN', $roles)) {
                    return $this->redirectToRoute('app_admin');
                } 
                else {
                    return $this->redirectToRoute('app_dashbord');

                }
            }       }
    
        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('blank');
    }
}
