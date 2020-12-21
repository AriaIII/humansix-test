<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={"get"},
 *  itemOperations={
 *      "get"={"path"="/order/{id}"}
 *  },
 *  normalizationContext={"groups"={"order:read"}},
 * )
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("order:read")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("order:read")
     */
    private $orderDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("order:read")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("order:read")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="orderConcerned")
     * @Groups("order:read")
     */
    private $orderLines;

    /**
     * @ORM\Column(type="float")
     * @Groups("order:read")
     */
    private $price;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setorderConcerned($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getorderConcerned() === $this) {
                $orderLine->setorderConcerned(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
