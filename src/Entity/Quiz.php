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
    private $difficulte;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_question;

    /**
     * @ORM\Column(type="integer")
     */
    private $note_max;

    /**
     * @ORM\OneToMany(targetEntity=QuestionQuiz::class, mappedBy="quiz", orphanRemoval=true)
     */
    private $quizQuestions;

    /**
     * @ORM\OneToMany(targetEntity=NoteQuiz::class, mappedBy="quiz", orphanRemoval=true)
     */
    private $quizNotes;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="quizzes")
     */
    private $categorie;

    public function __construct()
    {
        $this->quizQuestions = new ArrayCollection();
        $this->quizNotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDifficulte(): ?string
    {
        return $this->difficulte;
    }

    public function setDifficulte(string $difficulte): self
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function getNbrQuestion(): ?int
    {
        return $this->nbr_question;
    }

    public function setNbrQuestion(int $nbr_question): self
    {
        $this->nbr_question = $nbr_question;

        return $this;
    }

    public function getNoteMax(): ?int
    {
        return $this->note_max;
    }

    public function setNoteMax(int $note_max): self
    {
        $this->note_max = $note_max;

        return $this;
    }

    /**
     * @return Collection|QuestionQuiz[]
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuestionQuiz $quizQuestion): self
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions[] = $quizQuestion;
            $quizQuestion->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizQuestion(QuestionQuiz $quizQuestion): self
    {
        if ($this->quizQuestions->removeElement($quizQuestion)) {
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuiz() === $this) {
                $quizQuestion->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NoteQuiz[]
     */
    public function getQuizNotes(): Collection
    {
        return $this->quizNotes;
    }

    public function addQuizNote(NoteQuiz $quizNote): self
    {
        if (!$this->quizNotes->contains($quizNote)) {
            $this->quizNotes[] = $quizNote;
            $quizNote->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizNote(NoteQuiz $quizNote): self
    {
        if ($this->quizNotes->removeElement($quizNote)) {
            // set the owning side to null (unless already changed)
            if ($quizNote->getQuiz() === $this) {
                $quizNote->setQuiz(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
