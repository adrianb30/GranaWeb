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
        $productos = $productoRepository->findAll();
        
        if( isset($_POST['buscador']) ){
            $productos = $productoRepository->findbyNombre($_POST['clave']);
            return $this->render('tienda/index.html.twig', [
                'productos' => $productos,
            ]);
        }
        return $this->render('tienda/index.html.twig', [
            'productos' => $productos,
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
