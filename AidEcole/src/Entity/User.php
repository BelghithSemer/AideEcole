<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_etabli = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_form = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $local = null;

    #[ORM\Column(nullable: true)]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rib = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $doc_verif = null; 


    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $endDateSub = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;


#[ORM\ManyToOne(inversedBy: 'users')]
private ?Gouvernorat $gouvernorat = null;

    #[ORM\ManyToMany(targetEntity: Cours::class, inversedBy: 'parents')]
    #[ORM\JoinTable(name: 'users_cours_participe')] // Optionnel : nom de la table de jointure
    private Collection $coursParticipe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $listFavorisName = null;


    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $paymentStatus = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vote::class, orphanRemoval: true)]
    private Collection $votes;

    #[ORM\ManyToMany(targetEntity: Annonce::class, mappedBy: 'participants')]
    private Collection $participations;

public function __construct1()
{
    $this->coursParticipe = new ArrayCollection();
    $this->votes = new ArrayCollection();
    $this->participations = new ArrayCollection();
    
}

public function getVotes(): Collection
{
    return $this->votes;
}

public function addVote(Vote $vote): self
{
    if (!$this->votes->contains($vote)) {
        $this->votes[] = $vote;
        $vote->setUser($this);
    }
    return $this;
}

public function removeVote(Vote $vote): self
{
    if ($this->votes->removeElement($vote)) {
        if ($vote->getUser() === $this) {
            $vote->setUser(null);
        }
    }
    return $this;
}
// Getters et Setters
public function getPaymentStatus(): ?string
{
    return $this->paymentStatus;
}

public function setPaymentStatus(?string $paymentStatus): self
{
    $this->paymentStatus = $paymentStatus;
    return $this;
}
public function getEndDateSub(): ?\DateTimeInterface
    {
        return $this->endDateSub;
    }

    public function setEndDateSub(?\DateTimeInterface $endDateSub): self
    {
        $this->endDateSub = $endDateSub;
        return $this;
    }
public function getCoursParticipe(): Collection
{
    return $this->coursParticipe;
}

public function addCoursParticipe(Cours $cours): self
{
    if (!$this->coursParticipe->contains($cours)) {
        $this->coursParticipe->add($cours);
        $cours->addParent($this); // Mise à jour bidirectionnelle
    }
    return $this;
}

public function removeCoursParticipe(Cours $cours): self
{
    if ($this->coursParticipe->removeElement($cours)) {
        $cours->removeParent($this); // Mise à jour bidirectionnelle
    }
    return $this;
}



public function getDocVerif(): ?string
{
    return $this->doc_verif;
}

public function setDocVerif(?string $doc_verif): static
{
    $this->doc_verif = $doc_verif;
    return $this;
}


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static

    {
        $this->email = $email;

        return $this;
    }

    public function getListFavorisName(): ?string
    {
        return $this->listFavorisName;
    }

    public function setListFavorisName(?string $listFavorisName): static
    {
        $this->listFavorisName = $listFavorisName;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email; 
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    public function getNomEtabli(): ?string
    {
        return $this->nom_etabli;
    }

    public function setNomEtabli(?string $nom_etabli): static
    {
        $this->nom_etabli = $nom_etabli;

        return $this;
    }

    public function getNomForm(): ?string
    {
        return $this->nom_form;
    }

    public function setNomForm(?string $nom_form): static
    {
        $this->nom_form = $nom_form;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(?string $local): static
    {
        $this->local = $local;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(?int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): static
    {
        $this->rib = $rib;

        return $this;
    }


    #[ORM\Column(type: 'boolean', insertable: false, updatable: false)]
    private bool $agreeTerms = false;

    /**
     * @var Collection<int, Quiz>
     */
    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'id_user')]
    private Collection $quizzes;

    /**
     * @var Collection<int, Forum>
     */
    #[ORM\OneToMany(targetEntity: Forum::class, mappedBy: 'Forum_User')]
    private Collection $forums;

    #[ORM\ManyToOne(inversedBy: 'Parents')]
    private ?Annonce $annonce = null;

    /**
     * @var Collection<int, Bons>
     */
    #[ORM\OneToMany(targetEntity: Bons::class, mappedBy: 'id_user')]
    private Collection $bons;

    public function __construct()
    {
        $this->quizzes = new ArrayCollection();
        $this->forums = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->listFavorisName =  "Mes Favoris";
        $this->bons = new ArrayCollection();
    }

    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // Set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }
    // Getter
    public function getAgreeTerms(): bool
    {
        return $this->agreeTerms;
    }

    // Setter
    public function setAgreeTerms(bool $agreeTerms): self
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    public function eraseCredentials(): void
    {
        
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes->add($quiz);
            $quiz->setIdUser($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->quizzes->removeElement($quiz)) {
            if ($quiz->getIdUser() === $this) {
                $quiz->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Forum>
     */
    public function getForums(): Collection
    {
        return $this->forums;
    }

    public function addForum(Forum $forum): static
    {
        if (!$this->forums->contains($forum)) {
            $this->forums->add($forum);
            $forum->setForumUser($this);
        }}
        
     /**       
     * @return Collection<int, Bons>
     */
    public function getBons(): Collection
    {
        return $this->bons;
    }

    public function addBon(Bons $bon): static
    {
        if (!$this->bons->contains($bon)) {
            $this->bons->add($bon);
            $bon->setIdUser($this);
        }

        return $this;
    }

    public function removeForum(Forum $forum): static
    {
        if ($this->forums->removeElement($forum)) {
            // set the owning side to null (unless already changed)
            if ($forum->getForumUser() === $this) {
                $forum->setForumUser(null);
            }
        }

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): static
    {
        $this->annonce = $annonce;

        return $this;
    }


            
    public function removeBon(Bons $bon): static
    {
        if ($this->bons->removeElement($bon)) {
            // set the owning side to null (unless already changed)
            if ($bon->getIdUser() === $this) {
                $bon->setIdUser(null);
            }
        }

        return $this;
    }
    public function getGouvernorat(): ?gouvernorat
    {
        return $this->gouvernorat;
    }

    public function setGouvernorat(?gouvernorat $gouvernorat): static
    {
        $this->gouvernorat = $gouvernorat;

        return $this;
    }

    
    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $favoris;

    public function getFavoris(): Collection
{
    return $this->favoris;
}

public function addFavoris(Favoris $favoris): self
{
    if (!$this->favoris->contains($favoris)) {
        $this->favoris->add($favoris);
        $favoris->setUser($this);
    }
    return $this;
}

public function removeFavoris(Favoris $favoris): self
{
    if ($this->favoris->removeElement($favoris)) {
        // Set the owning side to null (unless already changed)
        if ($favoris->getUser() === $this) {
            $favoris->setUser(null);
        }
    }
    return $this;
}

public function getParticipations(): Collection
    {
        return $this->participations;
    }

    // Add a participation
    public function addParticipation(Annonce $annonce): self
    {
        if (!$this->participations->contains($annonce)) {
            $this->participations[] = $annonce;
            $annonce->addParticipant($this);
        }
        return $this;
    }

    // Remove a participation
    public function removeParticipation(Annonce $annonce): self
    {
        if ($this->participations->removeElement($annonce)) {
            $annonce->removeParticipant($this);
        }
        return $this;
    }


}
