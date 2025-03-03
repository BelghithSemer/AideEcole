<?php
namespace App\Controller;

use App\Entity\Signalement;
use App\Form\SignalementType;
use App\Repository\SignalementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/signalement')]
final class SignalementController extends AbstractController
{
   

    #[Route('', name: 'app_signalement_index', methods: ['GET'])]
    public function index(SignalementRepository $signalementRepository): Response
    {
        $user = $this->getUser();

        if ($user) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->render('Admin/signalement/signalement.html.twig', [
                    'signalements' => $signalementRepository->findAll(),
                    'user' => $user,
                ]);
            }
else {
    if (in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles())) {
        $signalements = $signalementRepository->createQueryBuilder('s')
            ->where('s.responsable = :user')
            ->andWhere('s.etat != :etat')
            ->setParameter('user', $user)
            ->setParameter('etat', 'Terminé')
            ->getQuery()
            ->getResult();

        return $this->render('signalement/listeSignalementResponsable.html.twig', [
            'signalements' => $signalements,
        ]);
    }

}
         
        }

        $signalements = $signalementRepository->createQueryBuilder('s')
            ->where('s.etat != :etat')
            ->andWhere('s.reste != :reste')
            ->setParameter('etat', 'Terminé')
            ->setParameter('reste', 0)
            ->getQuery()
            ->getResult();

            return $this->render('signalement/listeSignalement.html.twig', [
                'signalements' => $signalements,
            ]);
    }

    
    #[Route('/new', name: 'app_signalement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $signalement = new Signalement();
        $signalement->setEtat('en attente');

        $form = $this->createForm(SignalementType::class, $signalement, ['is_edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if ($user && in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles())) {
                $signalement->setResponsable($user);
            } else {
                $this->addFlash('error', 'Vous devez être un responsable pour soumettre un signalement.');
                return $this->redirectToRoute('app_login');
            }

            // Traitement des images
            $imageFiles = $form->get('images')->getData();
            if ($imageFiles) {
                $imagePaths = [];
                foreach ($imageFiles as $imageFile) {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    $imageFile->move($this->getParameter('images_signalements_directory'), $newFilename);
                    $imagePaths[] = $newFilename;
                }
                $signalement->setImages($imagePaths);
            }

            $entityManager->persist($signalement);
            $entityManager->flush();

            return $this->redirectToRoute('app_signalement_index');
        }

        return $this->render('signalement/ajoutSignalement.html.twig', [
            'signalement' => $signalement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_signalement_show', methods: ['GET'])] 
    public function show(Signalement $signalement): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
    
        if ($user && in_array('ROLE_RESPONSABLE_ETABLISSEMENT', $user->getRoles())) {
            return $this->render('signalement/signalementDetailsResponsable.html.twig', [
                'signalement' => $signalement,
            ]);
        }
    
        return $this->render('signalement/signalementDetails.html.twig', [
            'signalement' => $signalement,
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_signalement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SignalementType::class, $signalement, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
            if ($imageFiles) {
                $imagePaths = [];
                foreach ($imageFiles as $imageFile) {
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    $imageFile->move($this->getParameter('images_signalements_directory'), $newFilename);
                    $imagePaths[] = $newFilename;
                }
                $signalement->setImages($imagePaths);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_signalement_index');
        }

        return $this->render('signalement/modifSignalement.html.twig', [
            'signalement' => $signalement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_signalement_delete', methods: ['POST'])]
    public function delete(Request $request, Signalement $signalement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $signalement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($signalement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_signalement_index');
    }
}
