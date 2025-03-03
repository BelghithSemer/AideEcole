<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ description ne peut pas être vide.")]
    private ?string $Description = null;


   
    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Annonce $Commentaire_Annonce = null;



    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $createdAt): static
    {
        $this->date = $createdAt;
        return $this;
    }

    // Getter and Setter for user
    

    public function __construct()
    {
        // Automatically set the creation date when a new comment is created
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUser(): ?User // Return User instead of UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): static // Accept User instead of UserInterface
    {
        $this->user = $user;
        return $this;
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

   

    public function getCommentaireAnnonce(): ?Annonce
    {
        return $this->Commentaire_Annonce;
    }

    public function setCommentaireAnnonce(?Annonce $Commentaire_Annonce): static
    {
        $this->Commentaire_Annonce = $Commentaire_Annonce;

        return $this;
    }

   
}
