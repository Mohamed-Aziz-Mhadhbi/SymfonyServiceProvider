<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function __toString()
    {
        return $this->email;
    }

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @Assert\Length(min="3",max="25")
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $nom;

    /**
     * @Assert\Length(min="3",max="25")
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $prenom;

    /**
     * @Assert\Email()
     * @ORM\Column (type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column (type="string", columnDefinition="ENUM('admin', 'client', 'prestataire', 'entreprise')")
     */
    private $role;

    /**
     * @Assert\NotBlank()
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @Assert\Length (min="8", max="8")
     * @ORM\Column (type="integer", length=8, unique=true, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @Assert\Length (min="10", max="300")
     * @ORM\Column (type="string", length=300, nullable=true)
     */
    private $bio;

    /**
     * @Assert\Length(min="3",max="25")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $nomEntreprise;

    /**
     * @Assert\Length(min="3",max="200")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @Assert\Length(min="3",max="50")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $secteur;

    /**
     * @Assert\Length(min="3",max="50")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @Assert\Length(min="3",max="25")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $specialisation;

    /**
     * @Assert\Url()
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $siteWeb;

    /**
     * @Assert\Length(min="3",max="25")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $presentation;

    /**
     * @Assert\Length(min="3",max="25")
     * @ORM\Column (type="string", length=255, nullable=true)
     */
    private $taille;

    /**
     * @ORM\Column (type="integer", nullable=true)
     */
    private $montantHoraire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column (type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="User")
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity=Postulation::class, mappedBy="postulationUser")
     */
    private $postulations;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, mappedBy="serviceUser")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="orderUser")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="projectUser")
     */
    private $projects;

    /**
     * @ORM\ManyToMany(targetEntity=Skill::class, mappedBy="skillUser", fetch="EAGER")
     */
    private $skills;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="quizUser")
     */
    private $quizzes;

    /**
     * @ORM\ManyToMany(targetEntity=Domain::class, mappedBy="domainUser")
     */
    private $domains;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="user")
     */
    private $Likes;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->enabled = false;
        $this->offres = new ArrayCollection();
        $this->postulations = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
        $this->domains = new ArrayCollection();
        $this->Likes = new ArrayCollection();

    }




    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @return mixed
     */
    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return mixed
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @return mixed
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * @return mixed
     */
    public function getSiteWeb()
    {
        return $this->siteWeb;
    }

    /**
     * @return mixed
     */
    public function getSpecialisation()
    {
        return $this->specialisation;
    }

    /**
     * @return mixed
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getMontantHoraire()
    {
        return $this->montantHoraire;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;

    }

    /**
     * @param mixed $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio): void
    {
        $this->bio = $bio;
    }

    /**
     * @param mixed $nomEntreprise
     */
    public function setNomEntreprise($nomEntreprise): void
    {
        $this->nomEntreprise = $nomEntreprise;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @param mixed $presentation
     */
    public function setPresentation($presentation): void
    {
        $this->presentation = $presentation;
    }

    /**
     * @param mixed $secteur
     */
    public function setSecteur($secteur): void
    {
        $this->secteur = $secteur;
    }

    /**
     * @param mixed $siteWeb
     */
    public function setSiteWeb($siteWeb): void
    {
        $this->siteWeb = $siteWeb;
    }

    /**
     * @param mixed $specialisation
     */
    public function setSpecialisation($specialisation): void
    {
        $this->specialisation = $specialisation;
    }

    /**
     * @param mixed $taille
     */
    public function setTaille($taille): void
    {
        $this->taille = $taille;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @param mixed $montantHoraire
     */
    public function setMontantHoraire($montantHoraire): void
    {
        $this->montantHoraire = $montantHoraire;
    }



    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param false $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return false
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Postulation[]
     */
    public function getPostulations(): Collection
    {
        return $this->postulations;
    }

    public function addPostulation(Postulation $postulation): self
    {
        if (!$this->postulations->contains($postulation)) {
            $this->postulations[] = $postulation;
            $postulation->setPostulationUser($this);
        }

        return $this;
    }

    public function removePostulation(Postulation $postulation): self
    {
        if ($this->postulations->removeElement($postulation)) {
            // set the owning side to null (unless already changed)
            if ($postulation->getPostulationUser() === $this) {
                $postulation->setPostulationUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->addServiceUser($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            $service->removeServiceUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setOrderUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getOrderUser() === $this) {
                $order->setOrderUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setProjectUser($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getProjectUser() === $this) {
                $project->setProjectUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills[] = $skill;
            $skill->addSkillUser($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            $skill->removeSkillUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes[] = $quiz;
            $quiz->setQuizUser($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getQuizUser() === $this) {
                $quiz->setQuizUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Domain[]
     */
    public function getDomains(): Collection
    {
        return $this->domains;
    }

    public function addDomain(Domain $domain): self
    {
        if (!$this->domains->contains($domain)) {
            $this->domains[] = $domain;
            $domain->addDomainUser($this);
        }

        return $this;
    }

    public function removeDomain(Domain $domain): self
    {
        if ($this->domains->removeElement($domain)) {
            $domain->removeDomainUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getLikes(): Collection
    {
        return $this->Likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->Likes->contains($like)) {
            $this->Likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->Likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }
}