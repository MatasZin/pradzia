<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="order_date", type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\Column(name="order_end_date", type="datetime", nullable=true)
     */
    private $orderEndDate;

    /**
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="orders")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id", nullable=false)
     */
    private $car;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedService", mappedBy="orders")
     */
    private $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;
    }
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    public function setOrderEndDate($orderEndDate)
    {
        $this->orderEndDate = $orderEndDate;
    }
    public function getOrderEndDate()
    {
        return $this->orderEndDate;
    }

    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }
    public function getCompleted()
    {
        return $this->completed;
    }

    public function setCar($car)
    {
        $this->car = $car;
    }
    public function getCar()
    {
        return $this->car;
    }
}
