<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Entity\Favoris;
use App\Repository\FavorisRepository;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/cours')]
final class CoursController extends AbstractController
{
    #[Route('/{id<\d+>}', name: 'app_cours_by_matiere', methods: ['GET'])]
    public function index(CoursRepository $coursRepository, int $id): Response
    {
        $user = $this->getUser();

        // Filter cours by matiere ID
        $cours = $coursRepository->findBy(['Matiere' => $id]);

        return $this->render('cours/status_cours.html.twig', [
            'cours' => $cours,
            'user' => $user,
        ]);
    }
    
    #[Route('/all', name: 'app_cours_index', methods: ['GET'])]
    public function list(CoursRepository $coursRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        // Récupérer tous les cours
        $cours = $coursRepository->findAll();

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Cours/coursAdmin.html.twig', [
                'cours' => $cours,
                'user' => $user, 
            ]); 
        }

        return $this->render('cours/status_cours.html.twig', [
            'cours' => $cours,
            'user' => $user
        ]);
    }




    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'] , priority: 1)]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        Security $security, 
      
        #[Autowire('%pdf_directory%')] string $pdfDirectory
    ): Response {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);
        $user = $security->getUser();


        if ($form->isSubmitted()&& $form->isValid() ) {

            $pdfFile = $form->get('pdfFile')->getData();
            if ($pdfFile) {
                $newFilename = uniqid().'.'.$pdfFile->guessExtension();
                $pdfFile->move($pdfDirectory, $newFilename);
                $cours->setPdfFileName($newFilename);
            }
            
            // 🔹 Récupérer l'utilisateur connecté et l'assigner comme enseignant
            /*   $user = $security->getUser();
            if (!$user) {
                throw new \Exception("Utilisateur non authentifié.");
            }
            $cours->setEnseignant($user);

            // Récupérer les cours créés par cet enseignant seulement  
            $cours = $this->getDoctrine()  
                ->getRepository(Cours::class)  
                ->findBy(['enseignant' => $user]);   */

            $entityManager->persist($cours);
            $entityManager->flush();

            $matiereId = $cours->getMatiere() ? $cours->getMatiere()->getId() : null;

            // 🔹 Ajouter un message flash de succès
            // $this->addFlash('success', 'Le cours a été ajouté avec succès !');

    
            return $this->redirectToRoute('app_cours_index'); };


            // if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            //     return $this->render('Admin/Cours/ajoutCoursAdmin.html.twig', [
            //         'form' => $form , 
            //         'user' => $user, 
                    
            //     ]);
            // }


        // 🔹 Renvoyer le formulaire en cas d’erreur
        // $cours = $coursRepository->findCoursByMatiereAndEnseignant($id, $enseignant->getId());

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Cours/ajoutCoursAdmin.html.twig', [
                'form' => $form->createView(),
                'user' => $user, 
            ]); 
        }

        return $this->render('cours/ajout.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    #[Route('/show/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cours): Response
    {   
        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cours, EntityManagerInterface $entityManager, Security $security): Response 
    {
        $existingPdf = $cours->getPdfFileName(); // Store the existing PDF filename
        $user = $this->getUser(); 
        $user = $security->getUser();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a new file is uploaded
            if ($cours->getPdfFile() === null) {
                // No new file uploaded, keep the old file
                $cours->setPdfFileName($existingPdf);
            }

            $entityManager->flush();
            $matiereId = $cours->getMatiere() ? $cours->getMatiere()->getId() : null;
            return $this->redirectToRoute('app_cours_index');
            // , Response::HTTP_SEE_OTHER
    }

     if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->render('Admin/Cours/modifCoursAdmin.html.twig', [
                'form' => $form->createView(),
                'cours' => $cours,
                'user' => $user, 
            ]); 
        }

    return $this->render('cours/edit_delete_ens.html.twig', [
        'cours' => $cours,
        'form' => $form,
        'user' => $user
    ]);
}


    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager , Security $security): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        if ($user->getRoles()[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('app_cours_index');

        }
        return $this->redirectToRoute('app_cours_index');
    }  

    #[Route('/parent/listcours/{id}', name: 'app_cours_parent_index', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function parentList(
        CoursRepository $coursRepository,
        MatiereRepository $matiereRepository,
        FavorisRepository $favorisRepository,
        ?int $id = null
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        // Get all courses (filtered by matiereId if provided)
        $cours = $id
            ? $coursRepository->findBy(['Matiere' => $id]) 
            : $coursRepository->findAll();

        // Get all favorited courses for the current user
        $favoritedCourseIds = [];
        $favoris = $favorisRepository->findBy(['user' => $user]);
        foreach ($favoris as $fav) {
            $favoritedCourseIds[] = $fav->getCourse()->getId();
        }

        return $this->render('cours/listeCoursParent.html.twig', [
            'cours' => $cours,
            'favoritedCourseIds' => $favoritedCourseIds,
            'user' => $user,
            'matiere' => $id ? $matiereRepository->find($id) : null,
        ]);
    }

    
    #[Route('/add-to-favorite/{coursId}', name: 'app_add_to_favorite', methods: ['POST'])]
    public function addToFavorite(int $coursId, CoursRepository $coursRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], 403);
        }

        $cours = $coursRepository->find($coursId);
        if (!$cours) {
            return new JsonResponse(['success' => false, 'message' => 'Course not found'], 404);
        }

        // Check if the user already has this course favorited
        $existingFavoris = $entityManager->getRepository(Favoris::class)->findOneBy([
            'user' => $user,
            'course' => $cours,
        ]);

        if ($existingFavoris) {
            return new JsonResponse(['success' => false, 'message' => 'Course already in favorites'], 409);
        }

        // Create a new Favoris object
        $favoris = new Favoris();
        $favoris->setUser($user);
        $favoris->setCourse($cours);

        $entityManager->persist($favoris);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Course added to favorites']);
    }

    #[Route('/remove-from-favorite/{coursId}', name: 'app_remove_from_favorite', methods: ['POST'])]
    public function removeFromFavorite(int $coursId, CoursRepository $coursRepository, FavorisRepository $favorisRepository, EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not logged in'], 403);
        }

        $cours = $coursRepository->find($coursId);
        if (!$cours) {
            return new JsonResponse(['success' => false, 'message' => 'Course not found'], 404);
        }

        // Find the Favoris object linking the user and the course
        $favoris = $favorisRepository->findOneBy([
            'user' => $user,
            'course' => $cours,
        ]);

        if (!$favoris) {
            return new JsonResponse(['success' => false, 'message' => 'Course is not in favorites'], 409);
        }

        // Remove the Favoris object (deletes the relationship)
        $entityManager->remove($favoris);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Course removed from favorites']);
    }
    

    #[Route('/parent/listcours/details/{id}', name: 'app_cours_details', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function parentListCoursDetails(CoursRepository $coursRepository, int $id): Response
    {
        $cour = $coursRepository->find($id);

        if (!$cour) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        return $this->render('cours/coursDetails.html.twig', [
            'cour' => $cour, // Passer un seul cours au template
        ]);
    }


    #[Route('/cours/{id}/download', name: 'cours_download', methods: ['GET'])]  
    public function download(Cours $cours): Response  
    {  
        return $this->file('pdfs/' . $cours->getPdfFileName());   
    }
    




    #[Route('/parent/{id}/favoris', name: 'app_cours_favoris', methods: ['POST'])]
    public function toggleFavoris(int $id, EntityManagerInterface $entityManager, Security $security, CoursRepository $coursRepository, FavorisRepository $favorisRepository): JsonResponse 
    {   
        $user = $security->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non authentifié'], Response::HTTP_UNAUTHORIZED);
        }

        $cours = $coursRepository->find($id);
        if (!$cours) {
            return new JsonResponse(['message' => 'Cours non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer ou créer la liste de favoris de l'utilisateur
        $favoris = $favorisRepository->findOneBy(['parent' => $user]);

        if (!$favoris) {
            $favoris = new Favoris();
            $favoris->setParent($user);
            $entityManager->persist($favoris);
        }

        // Vérifier si le cours est déjà dans les favoris
        if ($favoris->getListeFavoris()->contains($cours)) {
            $favoris->removeListeFavoris($cours);
            $action = 'removed';
        } else {
            $favoris->addListeFavoris($cours);
            $action = 'added';
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Favoris mis à jour', 'action' => $action]);
    }


}
