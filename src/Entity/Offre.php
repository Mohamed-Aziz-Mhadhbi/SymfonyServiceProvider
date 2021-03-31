<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creatAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="offres")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class, inversedBy="offres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domainOffer;

    /**
     * @ORM\OneToMany(targetEntity=Postulation::class, mappedBy="offre")
     */
    private $postulationOffret;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="post")
     */
    private $Likes;

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;


    public function __construct()
    {
        $this->postulationOffret = new ArrayCollection();
        $this->yes = new ArrayCollection();
        $this->creatAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function __toString():string
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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDomainOffer(): ?Domain
    {
        return $this->domainOffer;
    }

    public function setDomainOffer(?Domain $domainOffer): self
    {
        $this->domainOffer = $domainOffer;

        return $this;
    }

    /**
     * @return Collection|Postulation[]
     */
    public function getPostulationOffret(): Collection
    {
        return $this->postulationOffret;
    }

    public function addPostulationOffret(Postulation $postulationOffret): self
    {
        if (!$this->postulationOffret->contains($postulationOffret)) {
            $this->postulationOffret[] = $postulationOffret;
            $postulationOffret->setOffre($this);
        }

        return $this;
    }

    public function removePostulationOffret(Postulation $postulationOffret): self
    {
        if ($this->postulationOffret->removeElement($postulationOffret)) {
            // set the owning side to null (unless already changed)
            if ($postulationOffret->getOffre() === $this) {
                $postulationOffret->setOffre(null);
            }
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

    public function addLikes(PostLike $like): self
    {
        if (!$this->Likes->contains($like)) {
            $this->Likes[] = $like;
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLikes(PostLike $like): self
    {
        if ($this->yes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }
    /**
     * permet de savaoir si cet article est like par un utilisateur
     * @param User $user
     * @return boolean
     */
    public function islikedByUser(User $user ):bool{
        foreach ($this->Likes as $like)
        {
            if ($like->getUser() == $user)
                return true;
        }
        return false;

    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

}
