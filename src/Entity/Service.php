<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creatAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="services")
     */
    private $serviceUser;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="service")
     */
    private $serviceOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $serviceDomain;

    public function __construct()
    {
        $this->serviceUser = new ArrayCollection();
        $this->serviceOrder = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getServiceUser(): Collection
    {
        return $this->serviceUser;
    }

    public function addServiceUser(User $serviceUser): self
    {
        if (!$this->serviceUser->contains($serviceUser)) {
            $this->serviceUser[] = $serviceUser;
        }

        return $this;
    }

    public function removeServiceUser(User $serviceUser): self
    {
        $this->serviceUser->removeElement($serviceUser);

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getServiceOrder(): Collection
    {
        return $this->serviceOrder;
    }

    public function addServiceOrder(Order $serviceOrder): self
    {
        if (!$this->serviceOrder->contains($serviceOrder)) {
            $this->serviceOrder[] = $serviceOrder;
            $serviceOrder->setService($this);
        }

        return $this;
    }

    public function removeServiceOrder(Order $serviceOrder): self
    {
        if ($this->serviceOrder->removeElement($serviceOrder)) {
            // set the owning side to null (unless already changed)
            if ($serviceOrder->getService() === $this) {
                $serviceOrder->setService(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function getServiceDomain(): ?Domain
    {
        return $this->serviceDomain;
    }

    public function setServiceDomain(?Domain $serviceDomain): self
    {
        $this->serviceDomain = $serviceDomain;

        return $this;
    }
}
