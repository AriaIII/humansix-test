<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={"get"},
 *  itemOperations={
 *      "get"={"path"="/product/{id}"}
 *  },
 *  normalizationContext={"groups"={"product:read", "order:read"}},
 *  
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"product:read", "order:read"})
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"product:read", "order:read"})     * 
     * @ApiProperty(identifier=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:read", "order:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"product:read", "order:read"})
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="product")
     * @Groups("product:read")
     */
    private $orderLines;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
            $orderLine->setproduct($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getproduct() === $this) {
                $orderLine->setproduct(null);
            }
        }

        return $this;
    }
}
