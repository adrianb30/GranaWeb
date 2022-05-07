<?php

namespace App\Entity;

use App\Repository\CarritoDetalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarritoDetalleRepository::class)
 */
class CarritoDetalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=carrito::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrito;

    /**
     * @ORM\ManyToMany(targetEntity=producto::class)
     */
    private $producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    public function __construct()
    {
        $this->producto = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrito(): ?carrito
    {
        return $this->carrito;
    }

    public function setCarrito(?carrito $carrito): self
    {
        $this->carrito = $carrito;

        return $this;
    }

    /**
     * @return Collection<int, producto>
     */
    public function getProducto(): Collection
    {
        return $this->producto;
    }

    public function addProducto(producto $producto): self
    {
        if (!$this->producto->contains($producto)) {
            $this->producto[] = $producto;
        }

        return $this;
    }

    public function removeProducto(producto $producto): self
    {
        $this->producto->removeElement($producto);

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
}
