<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductoRepository;
use App\Entity\Producto;

class TiendaController extends AbstractController
{
    /**
     * @Route("/", name="app_tienda")
     */
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('tienda/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }
    /**
     * @Route("/tienda/{id}", name="app_tienda_show", methods={"GET"})
     */
    public function show(Producto $producto): Response
    {
        return $this->render('tienda/producto.html.twig', [
            'producto' => $producto,
        ]);
    }
}
