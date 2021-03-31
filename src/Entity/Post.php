<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Length (
     *     min="10",
     *     max="255",
     *     minMessage="this filed must be at minimum 10 caracters",
     *     maxMessage="this filed must be at maximum 255 caracters"
     *     )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *  @Assert\Length (
     *     min="10",
     *     max="10000",
     *     minMessage="this filed must be at minimum 10 caracters",
     *     maxMessage="this filed must be at maximum 10000 caracters"
     *     )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $creatAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $views;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noc;

    /**
     * @ORM\ManyToOne(targetEntity=Forum::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $frm;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usr;


    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="pst")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="tagPost")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statusLike;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $req_user;



    public function __construct()
    {

        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id . "__" . $this->title  ;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(?int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getNoc(): ?int
    {
        return $this->noc;
    }

    public function setNoc(?int $noc): self
    {
        $this->noc = $noc;

        return $this;
    }

    public function getFrm(): ?Forum
    {
        return $this->frm;
    }

    public function setFrm(?Forum $frm): self
    {
        $this->frm = $frm;

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


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPst($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPst() === $this) {
                $comment->setPst(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addTagPost($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeTagPost($this);
        }

        return $this;
    }

    public function getStatusLike(): ?bool
    {
        return $this->statusLike;
    }

    public function setStatusLike(?bool $statusLike): self
    {
        $this->statusLike = $statusLike;

        return $this;
    }

    public function getReqUser(): ?string
    {
        return $this->req_user;
    }

    public function setReqUser(?string $req_user): self
    {
        $this->req_user = $req_user;

        return $this;
    }
    public  function getAvgRating()
    {
        $sum = array_reduce($this->comments->toArray(), function($total, $comment) {
            return $total + $comment->getRating();
        },0);

        if(count($this->comments)>0) return $sum / count($this->comments);

        return 0;
    }
}
