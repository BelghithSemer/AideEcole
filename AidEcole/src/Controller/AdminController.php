<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CoursRepository;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('Admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    // #[Route('/courss', name: 'admin_cours_index', methods: ['GET'])]
    // public function Cours(CoursRepository $coursRepository): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
    //     return $this->render('admin/cours/index.html.twig', [
    //         'cours' => $coursRepository->findAll(),
    //     ]);
    // }

    // #[Route('/new', name: 'admin_cours_new', methods: ['GET', 'POST'])]
    // public function new(
    //     Request $request, 
    //     EntityManagerInterface $entityManager,
    //     #[Autowire('%pdf_directory%')] string $pdfDirectory
    // ): Response {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
    //     $cours = new Cours();
    //     $form = $this->createForm(CoursType::class, $cours);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $pdfFile = $form->get('pdfFile')->getData();
    //         if ($pdfFile) {
    //             $newFilename = uniqid().'.'.$pdfFile->guessExtension();
    //             $pdfFile->move($pdfDirectory, $newFilename);
    //             $cours->setPdfFileName($newFilename);
    //         }

    //         $entityManager->persist($cours);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Le cours a été ajouté avec succès !');
    //         return $this->redirectToRoute('admin_cours_index');
    //     }

    //     return $this->render('admin/cours/new.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'admin_cours_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response 
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
    //     $existingPdf = $cours->getPdfFileName();
    //     $form = $this->createForm(CoursType::class, $cours);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         if ($cours->getPdfFile() === null) {
    //             $cours->setPdfFileName($existingPdf);
    //         }

    //         $entityManager->flush();
    //         $this->addFlash('success', 'Le cours a été modifié avec succès !');
    //         return $this->redirectToRoute('admin_cours_index');
    //     }

    //     return $this->render('admin/cours/edit.html.twig', [
    //         'cours' => $cours,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/{id}', name: 'admin_cours_delete', methods: ['POST'])]
    // public function delete(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
    //     if ($this->isCsrfTokenValid('delete'.$cours->getId(), $request->getPayload()->getString('_token'))) {
    //         // Supprimer le fichier PDF associé
    //         if ($cours->getPdfFileName()) {
    //             $pdfPath = $this->getParameter('pdf_directory') . '/' . $cours->getPdfFileName();
    //             if (file_exists($pdfPath)) {
    //                 unlink($pdfPath);
    //             }
    //         }
            
    //         $entityManager->remove($cours);
    //         $entityManager->flush();
            
    //         $this->addFlash('success', 'Le cours a été supprimé avec succès !');
    //     }

    //     return $this->redirectToRoute('admin_cours_index');
    // }

    // #[Route('/{id}/download', name: 'admin_cours_download', methods: ['GET'])]
    // public function download(Cours $cours): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
    //     $pdfPath = $this->getParameter('pdf_directory') . '/' . $cours->getPdfFileName();
        
    //     if (!file_exists($pdfPath)) {
    //         throw $this->createNotFoundException('Le fichier PDF n\'existe pas.');
    //     }
        
    //     return $this->file($pdfPath);
    // }

    // #[Route('/stats', name: 'admin_cours_stats', methods: ['GET'])]
    // public function stats(
    //     CoursRepository $coursRepository,
    //     MatiereRepository $matiereRepository
    // ): JsonResponse {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
    //     $stats = [
    //         'total_cours' => $coursRepository->count([]),
    //         'cours_par_matiere' => [],
    //         'cours_recents' => $coursRepository->findBy([], ['id' => 'DESC'], 5),
    //     ];

    //     foreach ($matiereRepository->findAll() as $matiere) {
    //         $stats['cours_par_matiere'][$matiere->getNom()] = $coursRepository->count(['Matiere' => $matiere]);
    //     }

    //     return new JsonResponse($stats);
    // }



}

