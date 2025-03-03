<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]  
    #[Assert\NotNull(message: "La date ne peut pas être vide.")]  
    private \DateTimeInterface $Date; 

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La question ne peut pas être vide.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "La question ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $Questions = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    #[Assert\Length(
        max: 200,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Images = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie ne peut pas être vide.")]
    private ?string $Categorie = null;

    #[ORM\ManyToOne(inversedBy: 'forums')]
    private ?User $Forum_User = null;

    #[ORM\OneToMany(mappedBy: 'forum', targetEntity: CommentaireForum::class, orphanRemoval: true)]
    private $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    /**
     */
    public function getCommentaires(): \Doctrine\Common\Collections\Collection
    {
        return $this->commentaires;
    }

    /**
     */
    public function addCommentaire(CommentaireForum $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setForum($this);
        }

        return $this;
    }

    /**
     */
    public function removeCommentaire(CommentaireForum $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            if ($commentaire->getForum() === $this) {
                $commentaire->setForum(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getQuestions(): ?string
    {
        return $this->Questions;
    }

    public function setQuestions(string $Questions): static
    {
        $this->Questions = $Questions;

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

    public function getImages(): ?string
    {
        return $this->Images;
    }

    public function setImages(string $Images): static
    {
        $this->Images = $Images;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): static
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getForumUser(): ?User
    {
        return $this->Forum_User;
    }

    public function setForumUser(?User $Forum_User): static
    {
        $this->Forum_User = $Forum_User;

        return $this;
    }
}