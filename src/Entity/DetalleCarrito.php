<?php

namespace App\Entity;

use App\Repository\DetalleCarritoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetalleCarritoRepository::class)
 */
class DetalleCarrito
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
    private $carrito_id;

    /**
     * @ORM\ManyToOne(targetEntity=producto::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $producto_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrito_id(): ?carrito
    {
        return $this->carrito_id;
    }

    public function setCarrito_id(?carrito $carrito): self
    {
        $this->carrito = $carrito_id;

        return $this;
    }

    public function getProducto_id(): ?producto
    {
        return $this->producto_id;
    }

    public function setProducto_id(?producto $producto): self
    {
        $this->producto = $producto_id;

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

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}
