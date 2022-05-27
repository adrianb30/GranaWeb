<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\PedidoRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/backoffice", name="app_admin")
     */
    public function index(PedidoRepository $pedidoRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'pedidos' => $pedidoRepository->findAllOrderedByFecha(),
        ]);
    }
}
