<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\HttpFoundation\File\UploadedFile; 
use Symfony\Component\Validator\Constraints\File; 

class RegistrationController extends AbstractController
{
    #[Route('/register/{role}', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        string $role = null 
    ): Response {
        $user = new User();
        
        
        switch ($role) {
            case 'donateur':
                $user->setRoles(['ROLE_DONATEUR']);
                break;
            case 'responsable_etablissement':
                $user->setRoles(['ROLE_RESPONSABLE_ETABLISSEMENT']);
                break;
            case 'parent':
                $user->setRoles(['ROLE_PARENT']);
                break;
            case 'centre_formation':
                $user->setRoles(['ROLE_CENTRE_FORMATION']);
                break;
            case 'enseignant':
                $user->setRoles(['ROLE_ENSEIGNANT']);
                break;
            default:
                return $this->redirectToRoute('app_role_selection');
        }

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'role' => $role, 
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
      
            $user->setAgreeTerms($form->get('agreeTerms')->getData());

       
            if ($role === 'responsable_etablissement') {
                /** @var UploadedFile|null $docFile */
                $docFile = $form->get('doc_verif')->getData();
                if ($docFile instanceof UploadedFile) {
                    $newFilename = uniqid() . '.' . $docFile->guessExtension();
                    $uploadsDirectory = $this->getParameter('uploads_directory');
                    
             
                    if (!is_dir($uploadsDirectory)) {
                        mkdir($uploadsDirectory, 0777, true);
                    }

                  
                    $docFile->move($uploadsDirectory, $newFilename);
                    $user->setDocVerif($newFilename);
                }
            }

      
            $password = $form->get('password')->getData();
            if ($password) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            } else {
                throw new \Exception(" Mot de passe ne peux pas être vide");
            }


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/signup.html.twig', [
            'registrationForm' => $form->createView(),
            'role' => $role,
        ]);
    }

    #[Route('/role-selection', name: 'app_role_selection')]
    public function roleSelection(): Response
    {
        return $this->render('registration/role_selection.html.twig');
    }
}
