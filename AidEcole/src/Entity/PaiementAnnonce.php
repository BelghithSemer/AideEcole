<?php

namespace App\Entity;

use App\Repository\PaiementAnnonceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementAnnonceRepository::class)]
class PaiementAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $Prix = null;

    #[ORM\Column]
    private ?int $numCarte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateValidite = null;

    #[ORM\ManyToOne(inversedBy: 'paiementAnnonces')]
    private ?User $PaiementAnnonce_id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Annonce $Annonce_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPrix(): ?float // Retourne un float, pas ?float
    {
        return $this->Prix  ;
    }

    public function setPrix(float $Prix): static // Accepte un float, pas ?float
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getNumCarte(): ?int
    {
        return $this->numCarte;
    }

    public function setNumCarte(int $numCarte): static
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    public function getDateValidite(): ?\DateTimeInterface
    {
        return $this->dateValidite;
    }

    public function setDateValidite(\DateTimeInterface $DateValidité): static
    {
        $this->dateValidite = $DateValidité;

        return $this;
    }

    public function getPaiementAnnonceId(): ?User
    {
        return $this->PaiementAnnonce_id;
    }

    public function setPaiementAnnonceId(?User $PaiementAnnonce_id): static
    {
        $this->PaiementAnnonce_id = $PaiementAnnonce_id;

        return $this;
    }

    public function getAnnonceId(): ?Annonce
    {
        return $this->Annonce_id;
    }

    public function setAnnonceId(?Annonce $Annonce_id): static
    {
        $this->Annonce_id = $Annonce_id;

        return $this;
    }
}
