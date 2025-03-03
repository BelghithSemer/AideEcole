<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Signalement;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\SignalementTermineRepository")]
class SignalementTermine
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Signalement", inversedBy: "signalementTermines")]
    #[ORM\JoinColumn(nullable: false)]
    private $signalement;

    #[ORM\Column(type: "array", nullable: true)]
   
    private $imagesApres = [];

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 1000,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSignalement(): ?Signalement
    {
        return $this->signalement;
    }

    public function setSignalement(Signalement $signalement): self
    {
        $this->signalement = $signalement;
        return $this;
    }

    public function getImagesApres(): ?array
    {
        return $this->imagesApres;
    }

    public function setImagesApres(?array $imagesApres): self
    {
        $this->imagesApres = $imagesApres;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
