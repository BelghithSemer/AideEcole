<?php

namespace App\Controller;

use App\Entity\Donation;
use App\Entity\Signalement;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/donation/{id}', name: 'donation_create')]
    public function createDonation(Signalement $signalement, Request $request): RedirectResponse
    {
        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour faire une donation.');
            return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion
        }

        // Récupérer le montant de la donation depuis le formulaire
        $montant = $request->request->get('montant');

        // Vérifier si le montant est bien un nombre et est supérieur à zéro
        if (!is_numeric($montant) || $montant <= 0) {
            $this->addFlash('error', 'Veuillez entrer un montant valide.');
            return $this->redirectToRoute('donation_create', ['id' => $signalement->getId()]);
        }

        // Vérifier que le montant ne dépasse pas le reste
        if ($montant > $signalement->getReste()) {
            $this->addFlash('error', 'Le montant de la donation ne peut pas être supérieur au reste à financer du signalement.');
            return $this->redirectToRoute('donation_create', ['id' => $signalement->getId()]);
        }

        // Créer la donation
        $donation = new Donation();
        $donation->setMontant($montant);
        $donation->setSignalement($signalement);
        $donation->setDonnateur($user);
        $donation->setDate(new \DateTime()); // Enregistrer la date actuelle

        // Sauvegarder la donation en base de données
        $this->entityManager->persist($donation);

        // Mettre à jour le reste à financer du signalement
        $signalement->setReste($signalement->getReste() - $montant);

        // Sauvegarder les modifications du signalement
        $this->entityManager->flush();

        $this->addFlash('success', 'Merci pour votre donation !');
        return $this->redirectToRoute('app_signalement_show', ['id' => $signalement->getId()]);
    }
}
