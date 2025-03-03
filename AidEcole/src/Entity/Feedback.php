<?php

namespace App\Entity;


use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "Le nom doit contenir au moins 3 caractères.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "Le prénom doit contenir au moins 3 caractères.")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis1 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis2 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis3 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis4 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis5 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis6 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis7 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis8 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis9 = null;

    #[ORM\Column(length: 255)]
   #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis10 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis11 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Length(min: 3, minMessage: "L'avis doit contenir au moins 3 caractères.")]
    private ?string $avis12 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse e-mail ne peut pas être vide.")]
    #[Assert\Email(message: "L'adresse e-mail n'est pas valide.")]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le niveau d'expérience ne peut pas être vide.")]
    private ?string $experience_level = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La recommandation de plateforme ne peut pas être vide.")]
    private ?string $recommend_platform = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La fréquence d'utilisation ne peut pas être vide.")]
    private ?string $usage_frequency = null;


    #[ORM\Column]
    #[Assert\NotBlank(message: "La note ne peut pas être vide.")]
    #[Assert\Range(
        min: 1, 
        max: 5, 
         notInRangeMessage: "La note doit être comprise entre {{ min }} et {{ max }}." )]
    private ?int $note = null; 


    #[ORM\ManyToOne(inversedBy: 'feedback')]
    private ?User $User_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAvis1(): ?string
    {
        return $this->avis1;
    }

    public function setAvis1(string $avis1): static
    {
        $this->avis1 = $avis1;

        return $this;
    }

    public function getAvis2(): ?string
    {
        return $this->avis2;
    }

    public function setAvis2(string $avis2): static
    {
        $this->avis2 = $avis2;

        return $this;
    }

    public function getAvis3(): ?string
    {
        return $this->avis3;
    }

    public function setAvis3(string $avis3): static
    {
        $this->avis3 = $avis3;

        return $this;
    }

    public function getAvis4(): ?string
    {
        return $this->avis4;
    }

    public function setAvis4(string $avis4): static
    {
        $this->avis4 = $avis4;

        return $this;
    }

    public function getAvis5(): ?string
    {
        return $this->avis5;
    }

    public function setAvis5(string $avis5): static
    {
        $this->avis5 = $avis5;

        return $this;
    }

    public function getAvis6(): ?string
    {
        return $this->avis6;
    }

    public function setAvis6(string $avis6): static
    {
        $this->avis6 = $avis6;

        return $this;
    }


    public function getAvis7(): ?string
    {
        return $this->avis7;
    }

    public function setAvis7(string $avis7): static
    {
        $this->avis7 = $avis7;

        return $this;
    }


    public function getAvis8(): ?string
    {
        return $this->avis8;
    }

    public function setAvis8(string $avis8): static
    {
        $this->avis8 = $avis8;

        return $this;
    }


    public function getAvis9(): ?string
    {
        return $this->avis9;
    }

    public function setAvis9(string $avis9): static
    {
        $this->avis9 = $avis9;

        return $this;
    }


    public function getAvis10(): ?string
    {
        return $this->avis10;
    }

    public function setAvis10(string $avis10): static
    {
        $this->avis10 = $avis10;

        return $this;
    }

    public function getAvis11(): ?string
    {
        return $this->avis11;
    }

    public function setAvis11(string $avis11): static
    {
        $this->avis11 = $avis11;

        return $this;
    }


    public function getAvis12(): ?string
    {
        return $this->avis12;
    }

    public function setAvis12(string $avis12): static
    {
        $this->avis12 = $avis12;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }


    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getExperienceLevel(): ?string  
    {  
        return $this->experience_level;  
    }  

    public function setExperienceLevel(?string $experience_level): self  
    {  
        $this->experience_level = $experience_level;  

        return $this;  
    }  

    public function getRecommendPlatform(): ?string  
    {  
        return $this->recommend_platform;  
    }  

    public function setRecommendPlatform(?string $recommend_platform): self  
    {  
        $this->recommend_platform = $recommend_platform;  

        return $this;  
    }  

    public function getUsageFrequency(): ?string  
    {  
        return $this->usage_frequency;  
    }  

    public function setUsageFrequency(?string $usage_frequency): self  
    {  
        $this->usage_frequency = $usage_frequency;  

        return $this;  
    }  

    public function getUserId(): ?User
    {
        return $this->User_id;
    }

    public function setUserId(?User $User_id): static
    {
        $this->User_id = $User_id;

        return $this;
    }
}
