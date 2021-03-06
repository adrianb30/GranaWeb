<?php

namespace App\Controller;

use App\Entity\Pedido;
use App\Form\PedidoType;
use App\Repository\PedidoRepository;
use App\Repository\ProductoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DetallePedido;
use App\Repository\DetallePedidoRepository;

/**
 * @Route("/backoffice/pedido")
 */
class PedidoController extends AbstractController
{
    /**
     * @Route("/", name="app_pedido_index", methods={"GET"})
     */
    public function index(PedidoRepository $pedidoRepository): Response
    {
        return $this->render('admin/pedido/index.html.twig', [
            'pedidos' => $pedidoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_pedido_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PedidoRepository $pedidoRepository, UserRepository $UserRepository, ProductoRepository $ProductoRepository, DetallePedidoRepository $DetallePedidoRepository): Response
    {
        $pedido = new Pedido();
        $time = strtotime('now');
        $newformat = date('Y-m-d',$time);
        //echo $newformat;
        $mensaje=[];
        //echo $fecha->format('Y M D') ;
        if (isset($_POST['crear'])) {
            $parada=(count($_POST)-2)/2;
            $pedido->setUsuario($UserRepository->findOneBy(['id'=> $_POST['cliente']]));
            $pedido->setFecha($time);
            $pedido->setEstado('Pendiente');
            $pedido->setTransaccionid("Compra en tienda");
            for ($i=1; $i <= $parada; $i++) {
                $producto=$ProductoRepository->findOneBy(['id'=>$_POST['producto'.$i]]);
                if ($producto->getStock() > 0 && $_POST['cantidadproducto'.$i] <= $producto->getStock() ) {
                    $pedidoRepository->add($pedido);
                    $detallepedido=new DetallePedido();
                    $detallepedido->setCantidad($_POST['cantidadproducto'.$i]);
                    $producto->setStock($producto->getStock() - $_POST['cantidadproducto'.$i]);
                    $ProductoRepository->add($producto);
                    $detallepedido->addProducto($producto);
                    $total=$producto->getPrecio()*$_POST['cantidadproducto'.$i];
                    $detallepedido->setTotal($total);
                    
                    $detallepedido->setPedido($pedido);
                    $DetallePedidoRepository->add($detallepedido);
                } else {
                    array_push($mensaje,$producto->getNombre().", cantidad disponible: ". $producto->getStock());
                }
            }
        }

        return $this->renderForm('admin/pedido/new.html.twig', [
            'pedido' => $pedido,
            'users' => $UserRepository->findAll(),
            'productos' => $ProductoRepository->findAll(),
            'mensaje' => $mensaje,
        ]);
    }

    /**
     * @Route("/{id}", name="app_pedido_show", methods={"GET"})
     */
    public function show(Pedido $pedido): Response
    {
        return $this->render('admin/pedido/show.html.twig', [
            'pedido' => $pedido,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_pedido_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Pedido $pedido, PedidoRepository $pedidoRepository): Response
    {
        
        if (isset($_POST['btn_actualizar'])) {
            //echo "<script>alert('".$_POST['pedido_id']."')</script>";
            $pedido=$pedidoRepository->find(['id'=>$_POST['pedido_id']]);
            $pedido->setEstado($_POST['estado']);
            $pedidoRepository->add($pedido);
            return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('admin/pedido/edit.html.twig', [
            'pedido' => $pedido,
        ]);
    }

    /**
     * @Route("/{id}", name="app_pedido_delete", methods={"POST"})
     */
    public function delete(Request $request, Pedido $pedido, PedidoRepository $pedidoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->request->get('_token'))) {
            $pedidoRepository->remove($pedido);
        }

        return $this->redirectToRoute('app_pedido_index', [], Response::HTTP_SEE_OTHER);
    }
}
