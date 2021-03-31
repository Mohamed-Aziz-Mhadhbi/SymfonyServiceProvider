<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creatAt;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank (message="this field must be full")
     * @Assert\Length (
     *     min="10",
     *     max="10000",
     *     minMessage="this filed must be at minimum 10 caracters",
     *     maxMessage="this filed must be at maximum 10000 caracters"
     *     )
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     */
    private $pst;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usr;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statusLike;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

    public function __construct()
    {
        $this->creatAt=new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatAt(): ?\DateTimeInterface
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeInterface $creatAt): self
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes=$likes;

        return $this;
    }

    public function getPst(): ?Post
    {
        return $this->pst;
    }

    public function setPst(?Post $pst): self
    {
        $this->pst = $pst;

        return $this;
    }

    public function getUsr(): ?User
    {
        return $this->usr;
    }

    public function setUsr(?User $usr): self
    {
        $this->usr = $usr;

        return $this;
    }

    public function getStatusLike(): ?bool
    {
        return $this->statusLike;
    }

    public function setStatusLike(bool $statusLike): self
    {
        $this->statusLike = $statusLike;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
