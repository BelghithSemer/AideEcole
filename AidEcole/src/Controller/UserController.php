<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('Admin/user/listuser.html.twig', [
            'users' => $userRepository->findAll(),
            'role' => 'responsable_etablissement',
            'role1' => 'parent',  
            'role2' => 'centre_formation',

        ]);
    }

    #[Route('/choose-role', name: 'app_user_choose_role', methods: ['GET', 'POST'])]
public function chooseRole(Request $request): Response
{
    $form = $this->createFormBuilder()
        ->add('role', ChoiceType::class, [
            'choices' => [
                'Admin' => 'ROLE_ADMIN',
                'Parent' => 'ROLE_PARENT',
                'Responsable Établissement' => 'ROLE_RESPONSABLE_ETABLISSEMENT',
                'Centre de Formation' => 'ROLE_CENTRE_FORMATION',
                'Utilisateur' => 'ROLE_USER',
            ],
            'expanded' => true, 
            'multiple' => false, 
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $role = $data['role'];

       
        $request->getSession()->set('selected_role', $role);

        return $this->redirectToRoute('app_user_new'); 
    }

    return $this->render('Admin/user/choose_role.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{

    $role = $request->getSession()->get('selected_role');

    if (!$role) {     
        return $this->redirectToRoute('app_user_choose_role');
    }

    $user = new User();
    $user->setRoles([$role]); 
    $form = $this->createForm(UserType::class, $user, [
        'request' => $request,
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('Admin/user/new.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }
    
        return $this->redirectToRoute('app_user_index');
    }
    
}
