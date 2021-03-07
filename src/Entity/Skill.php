<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 */
class Skill
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="skills")
     */
    private $skillUser;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class, inversedBy="skills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domainSkill;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="skill")
     */
    private $quizSkill;

    public function __toString()
    {
        return $this->title;
    }


    public function __construct()
    {
        $this->skillUser = new ArrayCollection();
        $this->quizSkill = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getSkillUser(): Collection
    {
        return $this->skillUser;
    }

    public function addSkillUser(User $skillUser): self
    {
        if (!$this->skillUser->contains($skillUser)) {
            $this->skillUser[] = $skillUser;
        }

        return $this;
    }

    public function removeSkillUser(User $skillUser): self
    {
        $this->skillUser->removeElement($skillUser);

        return $this;
    }

    public function getDomainSkill(): ?Domain
    {
        return $this->domainSkill;
    }

    public function setDomainSkill(?Domain $domainSkill): self
    {
        $this->domainSkill = $domainSkill;

        return $this;
    }

    /**
     * @return Collection|Quiz[]
     */
    public function getQuizSkill(): Collection
    {
        return $this->quizSkill;
    }

    public function addQuizSkill(Quiz $quizSkill): self
    {
        if (!$this->quizSkill->contains($quizSkill)) {
            $this->quizSkill[] = $quizSkill;
            $quizSkill->setSkill($this);
        }

        return $this;
    }

    public function removeQuizSkill(Quiz $quizSkill): self
    {
        if ($this->quizSkill->removeElement($quizSkill)) {
            // set the owning side to null (unless already changed)
            if ($quizSkill->getSkill() === $this) {
                $quizSkill->setSkill(null);
            }
        }

        return $this;
    }
}
