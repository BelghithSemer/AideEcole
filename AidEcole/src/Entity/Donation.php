<?php
// src/Entity/Donation.php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $code = null; // Code comme identifiant

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null; // Date de la donation

    #[ORM\Column(type: 'float')]
    private ?float $montant = null; // Montant de la donation

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'donations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $donnateur = null; // L'utilisateur qui a fait la donation

    #[ORM\ManyToOne(targetEntity: Signalement::class, inversedBy: 'donations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Signalement $signalement = null; // Le signalement associé à la donation

    public function __construct()
    {
        // Initialisation automatique de la date de donation lors de la création de l'objet
        $this->date = new \DateTime();
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;
        return $this;
    }

    public function getDonnateur(): ?User
    {
        return $this->donnateur;
    }

    public function setDonnateur(User $donnateur): static
    {
        $this->donnateur = $donnateur;
        return $this;
    }

    public function getSignalement(): ?Signalement
    {
        return $this->signalement;
    }

    public function setSignalement(Signalement $signalement): static
    {
        $this->signalement = $signalement;
        return $this;
    }

    // Ajout de la logique pour valider que le montant de la donation ne dépasse pas le prix du signalement
    public function isDonationValid(): bool
    {
        // Récupérer le prix du signalement (exemple: si prix est un champ dans Signalement)
        $prixSignalement = $this->signalement->getPrix();

        // Vérifier si le montant de la donation est inférieur ou égal au prix du signalement
        return $this->montant <= $prixSignalement;
    }
}
