<?php  

namespace App\Entity;  

use App\Repository\CommentaireForumRepository;  
use Doctrine\ORM\Mapping as ORM;  
use DateTimeImmutable;  
use Symfony\Component\Validator\Constraints as Assert;  
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity(repositoryClass: CommentaireForumRepository::class)]  
class CommentaireForum  
{  
    #[ORM\Id]  
    #[ORM\GeneratedValue]  
    #[ORM\Column]  
    private ?int $id = null;  

    #[ORM\Column(length: 255)]  
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]  
    #[Assert\Length(  
        max: 255,  
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."  
    )]  
    private ?string $Description = null;  

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commentaires')]  
    #[ORM\JoinColumn(nullable: false)]  
    private ?User $user = null;  

    // Created At Attribute  
    #[ORM\Column(type: 'datetime_immutable')]  
    private ?\DateTimeImmutable $createdAt = null;  

    #[ORM\ManyToOne(targetEntity: Forum::class, inversedBy: 'commentaires')]  
    #[ORM\JoinColumn(nullable: false)]  
    private ?Forum $forum = null;  


    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: Vote::class, orphanRemoval: true)]
    private Collection $votes;


    public function getId(): ?int  
    {  
        return $this->id;  
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

    public function getForum(): ?Forum  
    {  
        return $this->forum;  
    }  

    public function setForum(?Forum $forum): static  
    {  
        $this->forum = $forum;  

        return $this;  
    }  

    public function getUser(): ?User  
    {  
        return $this->user;  
    }  

    public function setUser(?User $user): static  
    {  
        $this->user = $user;  
        return $this;  
    }  

    public function getCreatedAt(): ?\DateTimeImmutable  
    {  
        return $this->createdAt;  
    }  

    public function setCreatedAt(\DateTimeImmutable $createdAt): static  
    {  
        $this->createdAt = $createdAt;  
        return $this;  
    }  

    public function __construct()  
    {  
        // Set the createdAt timestamp when the comment is created  
        $this->createdAt = new DateTimeImmutable();  
        $this->votes = new ArrayCollection();
    }  
    public function getVotes(): Collection
{
    return $this->votes;
}

public function addVote(Vote $vote): self
{
    if (!$this->votes->contains($vote)) {
        $this->votes[] = $vote;
        $vote->setComment($this);
    }
    return $this;
}

public function removeVote(Vote $vote): self
{
    if ($this->votes->removeElement($vote)) {
        if ($vote->getComment() === $this) {
            $vote->setComment(null);
        }
    }
    return $this;
}
}