<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;


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
    * @Assert\NotBlank
    */
    private $enonce;




    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\Choice(choices={1,2},message="La valeur doit etre soit 1 ou 2")
     */
    private $proposition_correcte;

    /**
     * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank
     */
    private $proposition_A;

    /**
     * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank
     */
    private $proposition_B;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnonce(): ?string
    {
        return $this->enonce;
    }

    public function setEnonce(string $enonce): self
    {
        $this->enonce = $enonce;
        

        return $this;
    }

   

    public function getPropositionCorrecte(): ?int
    {
        return $this->proposition_correcte;
    }

    public function setPropositionCorrecte(int $proposition_correcte): self
    {
        $this->proposition_correcte = $proposition_correcte;

        return $this;
    }

    public function getPropositionA(): ?string
    {
        return $this->proposition_A;
    }

    public function setPropositionA(string $proposition_A): self
    {
        $this->proposition_A = $proposition_A;

        return $this;
    }

    public function getPropositionB(): ?string
    {
        return $this->proposition_B;
    }

    public function setPropositionB(string $proposition_B): self
    {
        $this->proposition_B = $proposition_B;

        return $this;
    }

}
