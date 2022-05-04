<?php

namespace App\Controller;

use App\Entity\DetalleCarrito;
use App\Form\DetalleCarritoType;
use App\Repository\DetalleCarritoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/detalle/carrito")
 */
class DetalleCarritoController extends AbstractController
{
    /**
     * @Route("/", name="app_detalle_carrito_index", methods={"GET"})
     */
    public function index(DetalleCarritoRepository $detalleCarritoRepository): Response
    {
        return $this->render('detalle_carrito/index.html.twig', [
            'detalle_carritos' => $detalleCarritoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_detalle_carrito_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DetalleCarritoRepository $detalleCarritoRepository): Response
    {
        $detalleCarrito = new DetalleCarrito();
        $form = $this->createForm(DetalleCarritoType::class, $detalleCarrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detalleCarritoRepository->add($detalleCarrito);
            return $this->redirectToRoute('app_detalle_carrito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detalle_carrito/new.html.twig', [
            'detalle_carrito' => $detalleCarrito,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_detalle_carrito_show", methods={"GET"})
     */
    public function show(DetalleCarrito $detalleCarrito): Response
    {
        return $this->render('detalle_carrito/show.html.twig', [
            'detalle_carrito' => $detalleCarrito,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_detalle_carrito_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DetalleCarrito $detalleCarrito, DetalleCarritoRepository $detalleCarritoRepository): Response
    {
        $form = $this->createForm(DetalleCarritoType::class, $detalleCarrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detalleCarritoRepository->add($detalleCarrito);
            return $this->redirectToRoute('app_detalle_carrito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detalle_carrito/edit.html.twig', [
            'detalle_carrito' => $detalleCarrito,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_detalle_carrito_delete", methods={"POST"})
     */
    public function delete(Request $request, DetalleCarrito $detalleCarrito, DetalleCarritoRepository $detalleCarritoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detalleCarrito->getId(), $request->request->get('_token'))) {
            $detalleCarritoRepository->remove($detalleCarrito);
        }

        return $this->redirectToRoute('app_detalle_carrito_index', [], Response::HTTP_SEE_OTHER);
    }
}
