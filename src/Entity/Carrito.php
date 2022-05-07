<?php

namespace App\Entity;

use App\Repository\CarritoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarritoRepository::class)
 */
class Carrito
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="Carrito")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    // @ORM\OneToOne(targetEntity=user::class, inversedBy="carrito", cascade={"persist", "remove"})

     private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
