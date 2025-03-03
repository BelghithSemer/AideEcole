<?php

namespace App\Controller;

use App\Entity\SignalementTermine;
use App\Form\SignalementTermineType;
use App\Repository\SignalementRepository;
use App\Repository\SignalementTermineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignalementTermineController extends AbstractController
{
    #[Route("/signalements/termines", name: "signalement_termines")]
    public function index(SignalementRepository $signalementRepository, EntityManagerInterface $entityManager): Response
    {
        $signalementsTermines = $signalementRepository->findBy(['etat' => 'terminé']);
    
        $query = $entityManager->createQuery(
            'SELECT st FROM App\Entity\SignalementTermine st'
        );
        $signalementsTerminesAvecApres = $query->getResult();
    
        $signalementsAvecApresIds = array_map(fn($st) => $st->getSignalement()->getId(), $signalementsTerminesAvecApres);
    
        $signalementsSansApres = array_filter($signalementsTermines, fn($s) => !in_array($s->getId(), $signalementsAvecApresIds));
        
        return $this->render('signalement_termine/listeSignalementTermineResponsableSansApres.html.twig', [
            'signalementTermines' => $signalementsSansApres,
        ]);
    }

    #[Route("/signalements/termines/apres", name: "signalement_termines_apres")]
    public function signalementsAvecApres(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT st FROM App\Entity\SignalementTermine st WHERE st.imagesApres IS NOT NULL AND st.description IS NOT NULL'
        );
        $signalementsAvecApres = $query->getResult();
    
        return $this->render('signalement_termine/listeSignalementTermineResponsableApres.html.twig', [
            'signalementTermines' => $signalementsAvecApres,
        ]);
    }
    


   #[Route("/signalement/{id}/poster", name:"signalement_poster_images", methods:["GET", "POST"])]
     
    public function posterImages(
        int $id,
        SignalementRepository $signalementRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $signalement = $signalementRepository->find($id);

        if (!$signalement) {
            throw $this->createNotFoundException('Signalement non trouvé.');
        }

        $signalementTermine = new SignalementTermine();
        $signalementTermine->setSignalement($signalement);  

        $form = $this->createForm(SignalementTermineType::class, $signalementTermine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('imagesApres')->getData();
            if ($images) {
                $fileNames = [];
                foreach ($images as $image) {
                    $newFilename = uniqid() . '.' . $image->guessExtension();
                    $image->move(
                        $this->getParameter('images_signalements_directory'),
                        $newFilename
                    );
                    $fileNames[] = $newFilename;
                }

                $signalementTermine->setImagesApres($fileNames);
            }

            $entityManager->persist($signalementTermine);
            $entityManager->flush();

            $this->addFlash('success', 'Les images et la description ont été ajoutées avec succès.');

            return $this->redirectToRoute('signalement_termines_apres');
        }

        return $this->render('signalement_termine/ajoutImagesEtDescription.html.twig', [
            'form' => $form->createView(),
            'signalement' => $signalement,
        ]);
    }


    #[Route("/signalement/termine/{id}", name: "signalement_termine_show_Apres")]
    public function show(int $id, SignalementTermineRepository $signalementTermineRepository): Response
    {
        $signalementTermine = $signalementTermineRepository->find($id);

        if (!$signalementTermine) {
            throw $this->createNotFoundException('SignalementTermine non trouvé.');
        }
        return $this->render('signalement_termine/signalementTermineAvantApresDetails.html.twig', [
            'signalementTermine' => $signalementTermine,
        ]);
    }
    
}
