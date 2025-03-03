<?php

namespace App\Entity;

use App\Repository\PaiementCoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementCoursRepository::class)]
class PaiementCours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $Prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateValidité = null;

    #[ORM\Column]
    private ?int $numCarte = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'paiementCours')]
    private Collection $PaiementCours_Parent;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'paiementCours')]
    private Collection $PaiementCours_Cours;

    public function __construct()
    {
        $this->PaiementCours_Parent = new ArrayCollection();
        $this->PaiementCours_Cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): static
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getDateValidité(): ?\DateTimeInterface
    {
        return $this->DateValidité;
    }

    public function setDateValidité(\DateTimeInterface $DateValidité): static
    {
        $this->DateValidité = $DateValidité;

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

    /**
     * @return Collection<int, User>
     */
    public function getPaiementCoursParent(): Collection
    {
        return $this->PaiementCours_Parent;
    }

    public function addPaiementCoursParent(User $paiementCoursParent): static
    {
        if (!$this->PaiementCours_Parent->contains($paiementCoursParent)) {
            $this->PaiementCours_Parent->add($paiementCoursParent);
            $paiementCoursParent->setPaiementCours($this);
        }

        return $this;
    }

    public function removePaiementCoursParent(User $paiementCoursParent): static
    {
        if ($this->PaiementCours_Parent->removeElement($paiementCoursParent)) {
            // set the owning side to null (unless already changed)
            if ($paiementCoursParent->getPaiementCours() === $this) {
                $paiementCoursParent->setPaiementCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getPaiementCoursCours(): Collection
    {
        return $this->PaiementCours_Cours;
    }

    public function addPaiementCoursCour(Cours $paiementCoursCour): static
    {
        if (!$this->PaiementCours_Cours->contains($paiementCoursCour)) {
            $this->PaiementCours_Cours->add($paiementCoursCour);
            $paiementCoursCour->setPaiementCours($this);
        }

        return $this;
    }

    public function removePaiementCoursCour(Cours $paiementCoursCour): static
    {
        if ($this->PaiementCours_Cours->removeElement($paiementCoursCour)) {
            // set the owning side to null (unless already changed)
            if ($paiementCoursCour->getPaiementCours() === $this) {
                $paiementCoursCour->setPaiementCours(null);
            }
        }

        return $this;
    }
}
