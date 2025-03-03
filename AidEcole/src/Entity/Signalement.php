<?php
namespace App\Entity;

use App\Repository\SignalementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SignalementRepository::class)]
#[Vich\Uploadable]
class Signalement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide.")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    #[Assert\Length(
        min: 5,
        max: 1000,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'array', nullable: true)]
    private ?array $images = [];

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat = 'en attente'; // Valeur par défaut

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'signalements')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $responsable = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: "La gravité doit être entre {{ min }} et {{ max }}.")]
    private ?int $gravite = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Positive(message: "Le prix doit être un nombre positif.")]
    private ?float $prix = null;

    #[ORM\OneToMany(targetEntity:"App\Entity\SignalementTermine", mappedBy:"signalement")]
    private $signalementTermines;

    // Ajoutez la propriété `reste`
    #[ORM\Column(type: 'float')]
    #[Assert\Positive(message: "Le montant restant doit être un nombre positif.")]
    private ?float $reste = null;

    public function __construct()
    {
        $this->signalementTermines = new ArrayCollection();
    }

    public function getSignalementTermines(): Collection
    {
        return $this->signalementTermines;
    }

    // Getter et Setter pour `reste`
    public function getReste(): ?float
    {
        return $this->reste;
    }

    public function setReste(float $reste): static
    {
        $this->reste = $reste;
        return $this;
    }

    // Méthode pour initialiser `reste` avec le prix initial
    public function initReste(): void
    {
        $this->reste = $this->prix; // Le reste commence avec le prix total
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }
    
    public function setImages(?array $images): self
    {
        $this->images = $images;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    public function setResponsable(?User $responsable): static
    {
        $this->responsable = $responsable;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getGravite(): ?int
    {
        return $this->gravite;
    }

    public function setGravite(?int $gravite): static
    {
        $this->gravite = $gravite;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;
        if ($prix !== null) {
            $this->initReste(); // Ensure reste is initialized when prix is set
        }
        return $this;
    }
}
