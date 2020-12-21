<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderLineRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={"get"},
 *  itemOperations={"get"},
 *  normalizationContext={"groups"={"order:read"}},
 * )
 * @ORM\Entity(repositoryClass=OrderLineRepository::class)
 */
class OrderLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("order:read")
     */
    private $id;

    /**
     * order est un mot réservé dans MySQL donc j'ai nommé la proprété orderConcerned
     * 
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderConcerned;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orderLines")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("order:read")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     * @Groups("order:read")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getorderConcerned(): ?Order
    {
        return $this->orderConcerned;
    }

    public function setorderConcerned(?Order $orderConcerned): self
    {
        $this->orderConcerned = $orderConcerned;

        return $this;
    }

    public function getproduct(): ?Product
    {
        return $this->product;
    }

    public function setproduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
