<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
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

    #[ORM\Column(type: 'integer')]
    private int $likes = 0;

    #[ORM\Column(type: 'integer')]
    private int $dislikes = 0;


    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Annonce $Commentaire_Annonce = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'likedCommentaires')]
    #[ORM\JoinTable(name: 'commentaire_user_likes')]
    private Collection $likedByUsers;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'dislikedCommentaires')]
    #[ORM\JoinTable(name: 'commentaire_user_dislikes')]
    private Collection $dislikedByUsers;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->likedByUsers = new ArrayCollection();
        $this->dislikedByUsers = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;
        return $this;
    }

    public function getDislikes(): int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;
        return $this;
    }

    public function getLikedByUsers(): Collection
    {
        return $this->likedByUsers;
    }

    public function addLikedByUser(User $user): self
    {
        if (!$this->likedByUsers->contains($user)) {
            $this->likedByUsers[] = $user;
        }
        return $this;
    }

    public function removeLikedByUser(User $user): self
    {
        $this->likedByUsers->removeElement($user);
        return $this;
    }

    public function getDislikedByUsers(): Collection
    {
        return $this->dislikedByUsers;
    }

    public function addDislikedByUser(User $user): self
    {
        if (!$this->dislikedByUsers->contains($user)) {
            $this->dislikedByUsers[] = $user;
        }
        return $this;
    }

    public function removeDislikedByUser(User $user): self
    {
        $this->dislikedByUsers->removeElement($user);
        return $this;
    }

    public function incrementLikes(User $user): self
    {
        if (!$this->likedByUsers->contains($user)) {
            $this->likes++;
            $this->addLikedByUser($user);

            if ($this->dislikedByUsers->contains($user)) {
                $this->dislikes--;
                $this->removeDislikedByUser($user);
            }
        }
        return $this;
    }

    public function incrementDislikes(User $user): self
    {
        if (!$this->dislikedByUsers->contains($user)) {
            $this->dislikes++;
            $this->addDislikedByUser($user);

            if ($this->likedByUsers->contains($user)) {
                $this->likes--;
                $this->removeLikedByUser($user);
            }
        }
        return $this;
    }

    public function decrementLikes(User $user): self
    {
        if ($this->likedByUsers->contains($user)) {
            if ($this->likes > 0) {
                $this->likes--;
            }
            $this->removeLikedByUser($user);
        }
        return $this;
    }

    public function decrementDislikes(User $user): self
    {
        if ($this->dislikedByUsers->contains($user)) {
            if ($this->dislikes > 0) {
                $this->dislikes--;
            }
            $this->removeDislikedByUser($user);
        }
        return $this;
    }
}