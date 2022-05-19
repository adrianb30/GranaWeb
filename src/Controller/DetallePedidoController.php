<?php

namespace App\Controller;

use App\Entity\DetallePedido;
use App\Form\DetallePedidoType;
use App\Repository\DetallePedidoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/detalle/pedido")
 */
class DetallePedidoController extends AbstractController
{
    /**
     * @Route("/", name="app_detalle_pedido_index", methods={"GET"})
     */
    public function index(DetallePedidoRepository $detallePedidoRepository): Response
    {
        return $this->render('detalle_pedido/index.html.twig', [
            'detalle_pedidos' => $detallePedidoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_detalle_pedido_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DetallePedidoRepository $detallePedidoRepository): Response
    {
        $detallePedido = new DetallePedido();
        $form = $this->createForm(DetallePedidoType::class, $detallePedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detallePedidoRepository->add($detallePedido);
            return $this->redirectToRoute('app_detalle_pedido_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detalle_pedido/new.html.twig', [
            'detalle_pedido' => $detallePedido,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_detalle_pedido_show", methods={"GET"})
     */
    public function show(DetallePedido $detallePedido): Response
    {
        return $this->render('detalle_pedido/show.html.twig', [
            'detalle_pedido' => $detallePedido,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_detalle_pedido_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DetallePedido $detallePedido, DetallePedidoRepository $detallePedidoRepository): Response
    {
        $form = $this->createForm(DetallePedidoType::class, $detallePedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detallePedidoRepository->add($detallePedido);
            return $this->redirectToRoute('app_detalle_pedido_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detalle_pedido/edit.html.twig', [
            'detalle_pedido' => $detallePedido,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_detalle_pedido_delete", methods={"POST"})
     */
    public function delete(Request $request, DetallePedido $detallePedido, DetallePedidoRepository $detallePedidoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detallePedido->getId(), $request->request->get('_token'))) {
            $detallePedidoRepository->remove($detallePedido);
        }

        return $this->redirectToRoute('app_detalle_pedido_index', [], Response::HTTP_SEE_OTHER);
    }
}
