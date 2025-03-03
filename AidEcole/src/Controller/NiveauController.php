<?php

namespace App\Controller;

use App\Repository\NiveauRepository;
use App\Repository\CoursRepository;
use App\Repository\MatiereRepository;

//  Ajout de l'import manquant
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/niveau')]
final class NiveauController extends AbstractController
{
    #[Route('', name: 'app_niveau_index', methods: ['GET'])]
    public function index(NiveauRepository $niveauRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        $niveaux = $niveauRepository->findAll();
        return $this->render('matiere/liste_niveau.html.twig', [
            'niveaux' => $niveaux,
            'user' => $user
        ]);
    }

    #[Route('/quiz', name: 'app_niveau_quiz', methods: ['GET'])]
    public function quiz(NiveauRepository $niveauRepository): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        return $this->render('quiz/listniveau.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/parent', name: 'cours_parent_index', methods: ['GET'])]
    public function parentIndex(NiveauRepository $niveauRepository  ): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }
        return $this->render('niveau/listeNiveauParent.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
            'user' => $user , 
        ]);
    }



    #[Route('/parent/{id}/matiere', name: 'app_matiere_quiz_index', methods: ['GET'])]
    public function parentMatieres(MatiereRepository $niveauRepository , $id): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        return $this->render('quiz/matierequiz.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
            'user' => $user,
            'id' => $id
        ]);
    } 

    #[Route('/parent/{id}/matieres', name: 'app_matieres_index', methods: ['GET'])]
    public function parentMatieresCours(MatiereRepository $niveauRepository , $id): Response
    {
        $user = $this->getUser(); 

        if (!$user) {
            throw $this->createAccessDeniedException('No user logged in');
        }

        return $this->render('matiere/listeMatiereParent.html.twig', [
            'niveaux' => $niveauRepository->findAll(),
            'user' => $user,
            'id' => $id
           
        ]);
    } 

    /* Commentaire conservé pour l'exemple
    #[Route('/niveaux', name: 'app_niveaux_index', methods: ['GET'])]  
    public function listNiveaux(NiveauRepository $niveauRepository): Response  
    {  
        $niveaux = $niveauRepository->findAll();  
        return $this->render('niveau/listeNiveauParent.html.twig', [  
            'niveaux' => $niveaux,  
        ]);  
    }*/
}