<?php

namespace App\Entity;

use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomainRepository::class)
 */
class Domain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Offre::class, mappedBy="domainOffer")
     */
    private $offres;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="serviceDomain")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Skill::class, mappedBy="domainSkill")
     */
    private $skills;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="domains")
     */
    private $domainUser;

    public function __toString()
    {
        return $this->title;
    }


    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->domainUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
            $offre->setDomainOffer($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getDomainOffer() === $this) {
                $offre->setDomainOffer(null);
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
            $service->setServiceDomain($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getServiceDomain() === $this) {
                $service->setServiceDomain(null);
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
            $skill->setDomainSkill($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getDomainSkill() === $this) {
                $skill->setDomainSkill(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getDomainUser(): Collection
    {
        return $this->domainUser;
    }

    public function addDomainUser(User $domainUser): self
    {
        if (!$this->domainUser->contains($domainUser)) {
            $this->domainUser[] = $domainUser;
        }

        return $this;
    }

    public function removeDomainUser(User $domainUser): self
    {
        $this->domainUser->removeElement($domainUser);

        return $this;
    }
}
