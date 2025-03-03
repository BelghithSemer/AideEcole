<?php
namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    private $entityManager;
    private $security;
    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(EntityManagerInterface $entityManager, Security $security, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function edit(Request $request)
    {
        $user = $this->getUser();
    
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'isProfileEdit' => true, 
            'role' => in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles()) ? 'responsable_etablissement' : null,    
        ]);

        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('app_profile');
        }
    
        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/profile/change-password', name: 'app_change_password', methods: ['GET', 'POST'])]
public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
{
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ChangePasswordType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $currentPassword = $form->get('currentPassword')->getData();
        $newPassword = $form->get('newPassword')->getData();

   
        if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
            $this->addFlash('error', 'Mot de passe actuel incorrect.');
            return $this->redirectToRoute('app_change_password');
        }


        $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
        $this->entityManager->flush();

        $this->addFlash('success', 'Mot de passe mis à jour avec succès !');
        return $this->redirectToRoute('app_profile');
    }

    return $this->render('profile/change_password.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
}

}