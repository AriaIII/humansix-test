<?php

namespace App\Model;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

class CustomerModel 
{
    private $customer;
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getCustomers()
    {
        return $this->em->getRepository(Customer::class)->findAll();
    }

    public function getCustomer() {
        if(!$this->customer) {
            return null;
        }
        return $this->customer;
    }
    
    public function getCustomerById($id)
    { 
        $this->customer = $this->em->getRepository(Customer::class)->find($id);

        if(!$this->customer) {
            return null;
        }
        return $this->customer;
    }

    public function create($firstname, $lastname) {
        $customer = new Customer();

        $customer->setFirstname($firstname)
            ->setLastname($lastname)
        ;

        $this->em->persist($customer);
        $this->em->flush();

        $this->customer = $customer;
        return $this;
    }
}