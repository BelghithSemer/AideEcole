<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User; 
use App\Entity\Cours; 
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Cours::class, inversedBy: 'favoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $course = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCourse(): ?Cours
    {
        return $this->course;
    }

    public function setCourse(?Cours $course): self
    {
        $this->course = $course;
        return $this;
    }
}