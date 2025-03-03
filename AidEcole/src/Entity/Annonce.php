<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: "Le titre doit contenir au moins 5 caractères.",
        maxMessage: "Le titre ne peut pas dépasser 50 caractères."
    )]

    private ?string $Titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    #[Assert\Length(
        min: 10,
        max: 500,
        minMessage: "La description doit contenir au moins 10 caractères.",
        maxMessage: "La description ne peut pas dépasser 500 caractères."
    )]

    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nombre de places est obligatoire.")]
    #[Assert\Positive(message: "Le nombre de places doit être un nombre positif.")]

    private ?int $NbrePlace = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La localisation est obligatoire.")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "La localisation doit contenir au moins 2 caractères.",
        maxMessage: "La localisation ne peut pas dépasser 100 caractères."
    )]

    private ?string $Location = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'annonces')]
    private Collection $CentreFormation_id;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'Commentaire_Annonce')]
    private Collection $commentaires;


    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $views = 0; // Number of views

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $clicks = 0;


    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'participations')]
    #[ORM\JoinTable(name: 'annonce_participants')]
    private Collection $participants;


    public function __construct()
    {
        $this->CentreFormation_id = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }

    public function getNbrePlace(): ?int
    {
        return $this->NbrePlace;
    }

    public function setNbrePlace(int $NbrePlace): static
    {
        $this->NbrePlace = $NbrePlace;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(string $Location): static
    {
        $this->Location = $Location;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCentreFormationId(): Collection
    {
        return $this->CentreFormation_id;
    }

    public function addCentreFormationId(User $centreFormationId): static
    {
        if (!$this->CentreFormation_id->contains($centreFormationId)) {
            $this->CentreFormation_id->add($centreFormationId);
        }

        return $this;
    }

    public function removeCentreFormationId(User $centreFormationId): static
    {
        $this->CentreFormation_id->removeElement($centreFormationId);

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
{
    return $this->commentaires;
}

public function addCommentaire(Commentaire $commentaire): static
{
    if (!$this->commentaires->contains($commentaire)) {
        $this->commentaires->add($commentaire);
        $commentaire->setCommentaireAnnonce($this);
    }

    return $this;
}

public function removeCommentaire(Commentaire $commentaire): static
{
    if ($this->commentaires->removeElement($commentaire)) {
        // Set the owning side to null (unless already changed)
        if ($commentaire->getCommentaireAnnonce() === $this) {
            $commentaire->setCommentaireAnnonce(null);
        }
    }

    return $this;
}

public function getCommentCount(): int
    {
        return $this->commentaires->count();
    }

public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;
        return $this;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function setClicks(int $clicks): self
    {
        $this->clicks = $clicks;
        return $this;
    }

    // Increment methods for tracking
    public function incrementViews(): self
    {
        $this->views++;
        return $this;
    }

    public function incrementClicks(): self
    {
        $this->clicks++;
        return $this;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }
    public function addParticipant(User $user): self
    {
        if (!$this->participants->contains($user)) {
            // Check if there are available places
            if ($this->participants->count() < $this->NbrePlace) {
                $this->participants[] = $user;
                $user->addParticipation($this);
            } else {
                throw new \Exception('No available places left.');
            }
        }
        return $this;
    }

    // Remove a participant
    public function removeParticipant(User $user): self
    {
        if ($this->participants->removeElement($user)) {
            $user->removeParticipation($this);
        }
        return $this;
    }

    // Check if a user is already a participant
    public function isParticipant(User $user): bool
    {
        return $this->participants->contains($user);
    }
}