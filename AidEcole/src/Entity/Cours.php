<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File; 
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\User; 
use App\Entity\Niveau;  
use Symfony\Component\Validator\Constraints as Assert;  



#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[Vich\Uploadable]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]  
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]  
    #[Assert\Length(  
        min: 3,   
        max: 255,   
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères."  
    )]  
    #[Assert\Regex(  
        pattern: "/^[\p{L}0-9\s]+$/u",   
        message: "Le titre ne peut contenir que des lettres, des chiffres et des espaces."  
    )]  
    private ?string $Titre = null;

    #[ORM\Column(length: 255)]  
    #[Assert\NotBlank(message: "La description est obligatoire.")]  
    #[Assert\Length(  
        min: 5,   
        max: 65535,   
        minMessage: "La description doit contenir au moins {{ limit }} caractères."  
    )]  
    #[Assert\Regex(  
        pattern: "/^[\p{L}0-9\s]+$/u",   
        message: "La description ne peut contenir que des lettres, des chiffres et des espaces."  
    )]  
    private ?string $Description = null;

    #[ORM\Column]  
    #[Assert\NotBlank(message: "Le prix est obligatoire.")]  
    #[Assert\Positive(message: "Le prix doit être un nombre positif.")]  
    #[Assert\Range(
        min: 10,
        max: 50,
        notInRangeMessage: "Le prix doit être compris entre {{ min }} et {{ max }} dinars tunisiens.")]
    private ?float $Prix = null;
    

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    #[Vich\UploadableField(mapping: "cours_pdf", fileNameProperty: "pdfFileName")]   
    private ?File $pdfFile = null;

    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'course', orphanRemoval: true)]
    private Collection $favoris;

    public function getFavoris(): Collection
{
    return $this->favoris;
}

public function addFavoris(Favoris $favoris): self
{
    if (!$this->favoris->contains($favoris)) {
        $this->favoris->add($favoris);
        $favoris->setCourse($this);
    }
    return $this;
}

public function removeFavoris(Favoris $favoris): self
{
    if ($this->favoris->removeElement($favoris)) {
        // Set the owning side to null (unless already changed)
        if ($favoris->getCourse() === $this) {
            $favoris->setCourse(null);
        }
    }
    return $this;
}

    public function setPdfFile(?File $pdfFile = null): void
    {
        $this->pdfFile = $pdfFile;
        if ($pdfFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPdfFile(): ?File
    {
        return $this->pdfFile;
    }



    #[ORM\Column(type: "string", nullable: false)]
    private ?string $pdfFileName = null;

    public function getPdfFileName(): ?string
    {
        return $this->pdfFileName;
    }

    public function setPdfFileName(?string $pdfFileName): self
    {
        $this->pdfFileName = $pdfFileName;
        return $this;
    }
    


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $enseignant= null;

    public function getEnseignant(): ?User  
   {  return $this->enseignant;  }  


    public function setEnseignant(?User $enseignant): self  
   {  $this->enseignant = $enseignant;  
      return $this;  
}

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'coursParticipe')]    
    private Collection $parent;  

    public function __construct()  
    {  
        $this->parent = new ArrayCollection();  
        $this->favoris = new ArrayCollection();
    }  

    // Méthode getter pour parents  
    public function getParent(): Collection  
    {  
        return $this->parent;  
    }  

    // Ajoutez une méthode pour ajouter un parent (facultatif)  
    public function addParent(User $user): self  
    {  
        if (!$this->parent->contains($user)) {  
            $this->parent->add($user);  
            // Ajoutez également cette référence dans l'autre sens si nécessaire  
            $user->addCoursParticipe($this);  
        }  

        return $this;  
    }  

    // Ajoutez une méthode pour retirer un parent (facultatif)  
    public function removeParent(User $user): self  
    {  
        if ($this->parent->removeElement($user)) {  
            // Retirez également cette référence dans l'autre sens si nécessaire  
            $user->removeCoursParticipe($this);  
        }  

        return $this;  
    }  

    #[ORM\ManyToOne(inversedBy: 'cours')]
   
    private ?Matiere $Matiere = null;  
    
    public function getMatiere(): ?Matiere
    {
        return $this->Matiere;
    }

    public function setMatiere(?Matiere $Matiere_id): static
    {
        $this->Matiere = $Matiere_id;

        return $this;
    }



    #[ORM\ManyToOne(inversedBy: 'cours')]  
    
    private ?Niveau $niveau = null;  
    
    public function getNiveau(): ?Niveau  
    {  
        return $this->niveau;  
    }  
    
    public function setNiveau(?Niveau $niveau): static  
    {  
        $this->niveau = $niveau;  
    
        return $this;  
    }


    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'Commentaire_Cours')]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'PaiementCours_Cours')]
    private ?PaiementCours $paiementCours = null;

    public function __constructt()
    {
        $this->commentaires = new ArrayCollection();
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

    public function getPDF(): ?string
    {
        return $this->PDF;
    }

    public function setPDF(string $PDF): static
    {
        $this->PDF = $PDF;

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

    public function getEnseignantId(): ?User
    {
        return $this->Enseignant_id;
    }

    public function setEnseignantId(?User $Enseignant_id): static
    {
        $this->Enseignant_id = $Enseignant_id;

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
            $commentaire->setCommentaireCours($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getCommentaireCours() === $this) {
                $commentaire->setCommentaireCours(null);
            }
        }

        return $this;
    }

    public function getPaiementCours(): ?PaiementCours
    {
        return $this->paiementCours;
    }

    public function setPaiementCours(?PaiementCours $paiementCours): static
    {
        $this->paiementCours = $paiementCours;

        return $this;
    }
}
