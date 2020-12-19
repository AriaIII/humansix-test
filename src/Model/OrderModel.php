<?php

namespace App\Model;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Customer;
use App\Entity\OrderLine;
use Doctrine\ORM\EntityManagerInterface;

class OrderModel 
{
    private $order;
    private $orderLine;
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getOrderById($id)
    { 
        $this->order = $this->em->getRepository(Order::class)->find($id);

        if(!$this->order) {
            return null;
        }
        return $this->order;
    }

    public function create($id, $orderDate, $status, $price, Customer $customer) {
        $order = new Order();

        $order->setId($id)
            ->setOrderDate(new \DateTime($orderDate))
            ->setStatus($status)
            ->setPrice($price)
            ->setCustomer($customer)
        ;

        $this->em->persist($order);
        $this->em->flush();

        $this->order = $order;
        return $this;
    }

    public function createLine(Product $product, $quantity, Order $order) {
        $orderLine = new OrderLine();

        $orderLine->setOrderId($order)
            ->setProductId($product)
            ->setQuantity($quantity)
        ;

        $this->em->persist($orderLine);
        $this->em->flush();

        $this->orderLine = $orderLine;
        return $this;
    }
}