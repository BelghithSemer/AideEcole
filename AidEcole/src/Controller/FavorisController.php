<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Form\FavorisType;
use App\Repository\FavorisRepository;
use App\Entity\User;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security; 
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;


#[Route('/favoris')]
final class FavorisController extends AbstractController
{
    #[Route('/', name: 'app_favoris_indexx', methods: ['GET'])]
    public function index(FavorisRepository $favorisRepository, UserRepository $userRepository): Response
    {
        // Get the current user
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Access Denied.');
        }

        // Fetch the list of Favoris for the current user
        $favorisList = $favorisRepository->findBy(['user' => $user]);

        // Extract the associated Cours objects
        $favoritedCourses = array_map(function ($favoris) {
            return $favoris->getCourse();
        }, $favorisList);

      
        // Pass the favorited courses and the user's listFavorisName to the template
        return $this->render('favoris/index.html.twig', [
            'favoritedCourses' => $favoritedCourses,
            'listFavorisName' => $user->getListFavorisName(),
            'user' => $user
        ]);
    }

    #[Route('/favoris/edit-name', name: 'edit_list_name', methods: ['GET', 'POST'])]
    public function editListName(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException('Access Denied.');
        }

        if ($request->isMethod('POST')) {
            $newListName = $request->request->get('listFavorisName');
            $user->setListFavorisName($newListName);

            $entityManager->persist($user); // Use the injected EntityManager
            $entityManager->flush();

            // Add flash message manually (since we're not extending AbstractController)
            $request->getSession()->getFlashBag()->add('success', 'Le nom de la liste a été mis à jour avec succès.');

            return $this->redirectToRoute('app_favoris_indexx');
        }

        return $this->render('favoris/edit_list_name.html.twig', [
            'listFavorisName' => $user->getListFavorisName(),
            'user' => $user
        ]);
    }


    #[Route('/toggle-favorite/{coursId}', name: 'app_toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(
        int $coursId,
        CoursRepository $coursRepository,
        FavorisRepository $favorisRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], 403);
        }

        $cours = $coursRepository->find($coursId);
        if (!$cours) {
            return new JsonResponse(['success' => false, 'message' => 'Course not found'], 404);
        }

        // Check if the user already has this course favorited
        $favoris = $favorisRepository->findOneBy([
            'user' => $user,
            'course' => $cours,
        ]);

        if ($favoris) {
            // If the course is already favorited, remove it
            $entityManager->remove($favoris);
            $entityManager->flush();
            return new JsonResponse(['success' => true, 'message' => 'Course removed from favorites']);
        } else {
            // If the course is not favorited, add it
            $newFavoris = new Favoris();
            $newFavoris->setUser($user);
            $newFavoris->setCourse($cours);
            $entityManager->persist($newFavoris);
            $entityManager->flush();
            return new JsonResponse(['success' => true, 'message' => 'Course added to favorites']);
        }
    }

    #[Route('/new', name: 'app_favoris_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $favoris = new Favoris();
        $form = $this->createForm(FavorisType::class, $favoris);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($favoris);
            $entityManager->flush();

            return $this->redirectToRoute('app_favoris_indexx', [], Response::HTTP_SEE_OTHER);
        }

    
        return $this->render('favoris/new.html.twig', [
            'favoris' => $favoris,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favoris_show', methods: ['GET'])]
    public function show(Favoris $favoris): Response
    {
        return $this->render('favoris/show.html.twig', [
            'favoris' => $favoris,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_favoris_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Favoris $favoris, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FavorisType::class, $favoris);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_favoris_indexx', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('favoris/edit.html.twig', [
            'favoris' => $favoris,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_favoris_delete', methods: ['POST'])]
    public function delete(Request $request, Favoris $favoris, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favoris->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($favoris);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_favoris_indexx', [], Response::HTTP_SEE_OTHER);
    }


    

    #[Route('/cours/{id}/listfavoris', name: 'app_cours_favoris', methods: ['POST'])]
public function toggleFavoris(int $id, EntityManagerInterface $entityManager, Security $security, CoursRepository $coursRepository, FavorisRepository $favorisRepository): JsonResponse
{
    $user = $security->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'User not authenticated'], 403);
    }

    $cours = $coursRepository->find($id);
    if (!$cours) {
        return new JsonResponse(['error' => 'Cours not found'], 404);
    }

    // Vérifie si l'utilisateur a une liste de favoris
    $favoris = $favorisRepository->findOneBy(['parent' => $user]);
    if (!$favoris) {
        $favoris = new Favoris();
        $favoris->setParent($user);
        $entityManager->persist($favoris);
    }

    if ($favoris->getListeFavoris()->contains($cours)) {
        $favoris->removeListeFavoris($cours);
        $action = "removed";
    } else {
        $favoris->addListeFavoris($cours);
        $action = "added";
    }

    $entityManager->flush();

    return new JsonResponse(['action' => $action]);
}

}
