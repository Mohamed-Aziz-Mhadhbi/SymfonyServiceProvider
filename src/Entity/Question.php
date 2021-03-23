<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
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
    private $statement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerB;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerC;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rightAnswer;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="quizQuestion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatement(): ?string
    {
        return $this->statement;
    }

    public function setStatement(string $statement): self
    {
        $this->statement = $statement;

        return $this;
    }

    public function getAnswerA(): ?string
    {
        return $this->answerA;
    }

    public function setAnswerA(?string $answerA): self
    {
        $this->answerA = $answerA;

        return $this;
    }

    public function getAnswerB(): ?string
    {
        return $this->answerB;
    }

    public function setAnswerB(?string $answerB): self
    {
        $this->answerB = $answerB;

        return $this;
    }

    public function getAnswerC(): ?string
    {
        return $this->answerC;
    }

    public function setAnswerC(?string $answerC): self
    {
        $this->answerC = $answerC;

        return $this;
    }

    public function getRightAnswer(): ?string
    {
        return $this->rightAnswer;
    }

    public function setRightAnswer(string $rightAnswer): self
    {
        $this->rightAnswer = $rightAnswer;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
}
