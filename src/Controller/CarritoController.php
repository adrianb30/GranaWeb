<?php

namespace App\Controller;

use App\Entity\Carrito;
use App\Form\CarritoType;
use App\Repository\CarritoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/carrito")
 */
class CarritoController extends AbstractController
{
    /**
     * @Route("/", name="app_carrito_index", methods={"GET"})
     */
    public function index(CarritoRepository $carritoRepository): Response
    {
        return $this->render('carrito/index.html.twig', [
            'carritos' => $carritoRepository->findAll(),
        ]);
    }

    
    public function new(Request $request, CarritoRepository $carritoRepository, $id_user): Response
    {
        $carrito = new Carrito();
        $carrito->setUserId($id_user);
    }

    /**
     * @Route("/{id}", name="app_carrito_show", methods={"GET"})
     */
    public function show(Carrito $carrito): Response
    {
        return $this->render('carrito/show.html.twig', [
            'carrito' => $carrito,
        ]);
    }

    public function edit(Request $request, Carrito $carrito, CarritoRepository $carritoRepository): Response
    {
        $form = $this->createForm(CarritoType::class, $carrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carritoRepository->add($carrito);
            return $this->redirectToRoute('app_carrito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carrito/edit.html.twig', [
            'carrito' => $carrito,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_carrito_delete", methods={"POST"})
     */
    public function delete(Request $request, Carrito $carrito, CarritoRepository $carritoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrito->getId(), $request->request->get('_token'))) {
            $carritoRepository->remove($carrito);
        }

        return $this->redirectToRoute('app_carrito_index', [], Response::HTTP_SEE_OTHER);
    }
}
