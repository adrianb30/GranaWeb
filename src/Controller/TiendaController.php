<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductoRepository;
use App\Repository\CarritoDetalleRepository;
use App\Entity\Producto;
use App\Entity\Pedido;
use App\Entity\CarritoDetalle;
use App\Repository\PedidoRepository;
use App\Entity\DetallePedido;
use App\Repository\DetallePedidoRepository;

class TiendaController extends AbstractController
{
    /**
     * @Route("/", name="app_tienda")
     */
    public function index(ProductoRepository $productoRepository, CarritoDetalleRepository $CarritoDetalleRepository): Response
    {
        $productos = $productoRepository->findAll();
        $carrito=$CarritoDetalleRepository->findAll();
        $check=true;
        if( isset($_POST['buscador']) ){
            $productos = $productoRepository->findbyNombre($_POST['clave']);
            return $this->render('tienda/index.html.twig', [
                'productos' => $productos,
            ]);
        }
        if (isset($_POST['btn_carrito'])) {
            $producto = $productoRepository->findOneBy(['id'=>$_POST["producto"]]);
            $carrito=$CarritoDetalleRepository->findby(['carrito'=>$this->getUser()->getCarrito()->getId()]);
            foreach ($carrito as $detallecarrito) {
                foreach ($detallecarrito->getProducto() as $key) {
                    if ($key->getId() == $producto->getId()) {
                        $detallecarrito->setCantidad($detallecarrito->getCantidad()+1);
                        $detallecarrito->setTotal($detallecarrito->getCantidad()*$producto->getPrecio());
                        $check=false;
                        break;
                    }
                }
            }
            if (empty($carrito)) {
                $mensaje = $this->add_carrito($producto,$CarritoDetalleRepository);
            } else {
                if ($check == true) {
                    $mensaje = $this->add_carrito($producto,$CarritoDetalleRepository);
                } else {
                    $CarritoDetalleRepository->add($detallecarrito);
                    $mensaje="Este producto ya existe en el carrito se ha actualizado la cantidad";
                }
            }
            return $this->render('tienda/index.html.twig', [
                'productos' => $productos,
                'mensaje' => $mensaje,
            ]);
        }
        return $this->render('tienda/index.html.twig', [
            'productos' => $productos,
        ]);
    }
    /**
     * @Route("/tienda/{id}", name="app_tienda_show")
     */
    public function show(Producto $producto, ProductoRepository $productoRepository, CarritoDetalleRepository $CarritoDetalleRepository): Response
    {
        if (isset($_POST['btn_carrito'])) {
            $check=true;
            $producto = $productoRepository->findOneBy(['id'=>$_POST["producto"]]);
            $carrito=$CarritoDetalleRepository->findby(['carrito'=>$this->getUser()->getCarrito()->getId()]);
            foreach ($carrito as $detallecarrito) {
                foreach ($detallecarrito->getProducto() as $key) {
                    if ($key->getId() == $producto->getId()) {
                        $detallecarrito->setCantidad($detallecarrito->getCantidad()+1);
                        $detallecarrito->setTotal($detallecarrito->getCantidad()*$producto->getPrecio());
                        $check=false;
                        break;
                    }
                }
            }
            if (empty($carrito)) {
                $mensaje = $this->add_carrito($producto,$CarritoDetalleRepository);
            } else {
                if ($check == true) {
                    $mensaje = $this->add_carrito($producto,$CarritoDetalleRepository);
                } else {
                    $CarritoDetalleRepository->add($detallecarrito);
                    $mensaje="Este producto ya existe en el carrito se ha actualizado la cantidad";
                }
            }
        }
        return $this->render('tienda/producto.html.twig', [
            'producto' => $producto,
        ]);
    }
    
    public function add_carrito(Producto $producto, CarritoDetalleRepository $CarritoDetalleRepository)
    {
        $contenido= new CarritoDetalle();
        $contenido->setCarrito($this->getUser()->getCarrito());
        $contenido->setCantidad(1);
        $contenido->addProducto($producto);
        $precio=$producto->getPrecio();
        $total=$precio * $contenido->getCantidad();
        $contenido->setTotal($total);
        $CarritoDetalleRepository->add($contenido);
        
        return "Producto añadido correctamente al carrito";
        

        //var_dump($contenido->getProducto());
        
    }
    /**
     * @Route("/checkout", name="app_carrito")
     */
    public function carrito(CarritoDetalleRepository $CarritoDetalleRepository, ProductoRepository $productoRepository): Response
    {
        
        // $carrito=$CarritoDetalleRepository->findBy(['carrito' => $this->getUser()->getCarrito()->getId()]);
        
        
        if (isset($_POST['sum_carrito'])) {
            $carrito=$CarritoDetalleRepository->find(['id' => $_POST["id_producto"]]);
            $productos=$carrito->getProducto();
            $carrito->setCantidad($carrito->getCantidad()+1);
            foreach ($productos as $producto) {
                $carrito->setTotal($carrito->getCantidad() * $producto->getPrecio());
            }
            
            $CarritoDetalleRepository->add($carrito);
        }
        if (isset($_POST['rest_carrito'])) {
            $carrito=$CarritoDetalleRepository->find([ 'id' => $_POST["id_producto"]]);
            $productos=$carrito->getProducto();
            $carrito->setCantidad($carrito->getCantidad()-1);
            foreach ($productos as $producto) {
                $carrito->setTotal($carrito->getCantidad() * $producto->getPrecio());
            }
            
            $CarritoDetalleRepository->add($carrito);
            if ($carrito->getCantidad()==0) {
                $CarritoDetalleRepository->remove($carrito);
                
            }
        }
        $array=$CarritoDetalleRepository->countItems($this->getUser()->getCarrito()->getId());
        $result_fin=$array[0];
        $ver=true;
        foreach ($CarritoDetalleRepository->findBy(['carrito' => $this->getUser()->getCarrito()]) as $producto => $value) {
            foreach ($value->getProducto() as $key) {
                if ($key->getStock() > 0) {
                    $ver=true;
                } else {
                    $ver=false;
                    break;
                }
                if($ver==false){
                    break;
                }
            }
            
        }
        return $this->render('tienda/checkout.html.twig', [
            'user' => $this->getUser(),
            'carrito' => $CarritoDetalleRepository->findBy(['carrito' => $this->getUser()->getCarrito()]),
            'ver' => $ver,
            'total_items' => $result_fin,
            'total' => 0
        ]);
    }
    /**
     * @Route("/checkout/{id}", name="app_carrito_items_delete", methods={"POST"})
     */
    public function delete(Request $request, CarritoDetalle $CarritoDetalle, CarritoDetalleRepository $CarritoDetalleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$CarritoDetalle->getId(), $request->request->get('_token'))) {
            $CarritoDetalleRepository->remove($CarritoDetalle);
        }

        return $this->redirectToRoute('app_carrito', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/verificador", name="crear_pedido")
     */
    public function crearPedido(Request $request,CarritoDetalleRepository $CarritoDetalleRepository,ProductoRepository $ProductoRepository, PedidoRepository $pedidoRepository, DetallePedidoRepository $DetallePedidoRepository)
    {  
        $totalfinal=0;
        if ($_GET['status'] == 'COMPLETED') {
            $totalcarrito=$CarritoDetalleRepository->findby(["carrito" =>  $this->getUser()->getCarrito()->getId()]);
            $pedido=new pedido();
            $time = strtotime('now');
            $pedido->setUsuario($this->getUser());
            $pedido->setFecha($time);
            $pedido->setEstado($_GET['status']);
            $pedido->setTransaccionid($_GET['paymentToken']);
            
            foreach ($totalcarrito as $detalles => $detalle) {
                foreach ($detalle->getProducto() as $product) {
                    if ($product->getStock() > 0 && $detalle->getCantidad() <= $product->getStock() ) {
                        $pedidoRepository->add($pedido);
                        $detallepedido=new DetallePedido();
                        $detallepedido->setCantidad($detalle->getCantidad());
                        $product->setStock($product->getStock() - $detalle->getCantidad());
                        $ProductoRepository->add($product);
                        $detallepedido->addProducto($product);
                        $total=$product->getPrecio()*$detalle->getCantidad();
                        $detallepedido->setTotal($total);
                        $totalfinal+=$total;
                        $detallepedido->setPedido($pedido);
                        $producto=$detallepedido;
                        $DetallePedidoRepository->add($detallepedido);
                        $CarritoDetalleRepository->remove($detalle);
                    } else {
                        // array_push($mensaje,$product->getNombre().", cantidad disponible: ". $product->getStock());
                        return $this->redirectToRoute('app_final_compra_fallo', [], Response::HTTP_SEE_OTHER);
                    }
                }               
            }
        }
        return $this->redirectToRoute('app_final_compra_exito', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/finalizacionExito", name="app_final_compra_exito")
     */
    public function exito()
    {
        $mensaje="Se ha completado la compra correctamente";
        $clase="bg-success";
        return $this->render('tienda/verificacion.html.twig', [
            'mensaje' => $mensaje,
            'clase' => $clase,
        ]);
    }
    /**
     * @Route("/finalizacionFallo", name="app_final_compra_fallo")
     */
    public function fallo()
    {
        $mensaje="No se ha completado la compra correctamente";
        $clase="bg-warning";
        return $this->render('tienda/verificacion.html.twig', [
            'mensaje' => $mensaje,
            'clase' => $clase,
        ]);
    }

    /**
     * @Route("/mispedidos", name="app_mis_pedidos")
     */
    public function mispedidos()
    {
        $pedidos=$this->getUser()->getPedidos();
        return $this->render('tienda/mispedidos.html.twig', [
            'pedidos' => $pedidos,
        ]);
    }
    /**
     * @Route("/mispedidos/{id}", name="app_mis_pedido_show", methods={"GET"})
     */
    public function mispedidosdetalle(Pedido $pedido): Response
    {
        return $this->render('tienda/pedidosdetalles.html.twig', [
            'pedido' => $pedido,
        ]);
    }
    
}
