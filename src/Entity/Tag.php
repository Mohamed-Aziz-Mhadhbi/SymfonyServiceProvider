<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="this field must be full")
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank (message="this field must be full")
     * )
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, inversedBy="tags")
     */
    private $tagPost;



    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->tagPost = new ArrayCollection();
    }
    public function __toString() {
        return $this->title;
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

    /**
     * @return Collection|Post[]
     */
    public function getTagPost(): Collection
    {
        return $this->tagPost;
    }

    public function addTagPost(Post $tagPost): self
    {
        if (!$this->tagPost->contains($tagPost)) {
            $this->tagPost[] = $tagPost;
        }

        return $this;
    }

    public function removeTagPost(Post $tagPost): self
    {
        $this->tagPost->removeElement($tagPost);

        return $this;
    }


}
