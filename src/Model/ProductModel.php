<?php

namespace App\Model;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductModel 
{
    private $product;
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getProductBySku($sku) {
        $this->product = $this->em->getRepository(Product::class)->findOneBySku($sku);

        if(!$this->product) {
            return null;
        }
        return $this->product;
    }

    public function create($sku, $name, $price) {
        $product = new Product();

        $product->setSku($sku)
            ->setName($name)
            ->setPrice($price)
        ;

        $this->em->persist($product);
        $this->em->flush();

        $this->product = $product;
        return $this;
    }
}