<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $difficulty;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class, inversedBy="quizSkill")
     */
    private $skill;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="quizzes")
     */
    private $quizUser;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quiz")
     */
    private $quizQuestion;

    public function __toString()
    {
        return $this->name;
    }


    public function __construct()
    {
        $this->quizQuestion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTotalQuestion(): ?int
    {
        return $this->total_question;
    }

    public function setTotalQuestion(int $total_question): self
    {
        $this->total_question = $total_question;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getSkill(): ?Skill
    {
        return $this->skill;
    }

    public function setSkill(?Skill $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getQuizUser(): ?User
    {
        return $this->quizUser;
    }

    public function setQuizUser(?User $quizUser): self
    {
        $this->quizUser = $quizUser;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuizQuestion(): Collection
    {
        return $this->quizQuestion;
    }

    public function addQuizQuestion(Question $quizQuestion): self
    {
        if (!$this->quizQuestion->contains($quizQuestion)) {
            $this->quizQuestion[] = $quizQuestion;
            $quizQuestion->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizQuestion(Question $quizQuestion): self
    {
        if ($this->quizQuestion->removeElement($quizQuestion)) {
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuiz() === $this) {
                $quizQuestion->setQuiz(null);
            }
        }

        return $this;
    }
}
