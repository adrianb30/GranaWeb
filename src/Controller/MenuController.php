<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use App\Repository\CarritoDetalleRepository;

class MenuController extends AbstractController
{

    private $security;
    public function __construct(Security $security)
    {
         $this->security = $security;
    }
    /**
     * @var String $route_name
     *   Machine name of a route
     */
    public function mainMenu(String $route_name,CarritoDetalleRepository $CarritoDetalleRepository)
    {
        $items['inicio']['title'] = 'Inicio';
        $items['inicio']['url'] = $this->generateUrl('app_tienda');
        if ($route_name == 'inicio') {
            $items['inicio']['class'] = "active";
        }
        if( $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {
            // Only for authenticated users
            $array=$CarritoDetalleRepository->countItems($this->getUser()->getCarrito()->getId());
            $result_fin=$array[0];
            $special['Mi perfil']['title'] = 'Mi Perfil';
            $special['Mi perfil']['url'] = $this->generateUrl('app_perfil');
            if ($route_name == 'Mi Cuenta') {
                $special['perfil']['class'] = "active";
            }
            $special['cerrar_sesion']['title'] = 'Cerrar Sesión';
            $special['cerrar_sesion']['url'] = $this->generateUrl('app_logout');
            if ($route_name == 'cerrar_sesion') {
                $special['cerrar_sesion']['class'] = "active";
            }
            $carritos['carrito']['title'] = 'carrito';
            $carritos['carrito']['url'] = $this->generateUrl('app_carrito');
            $carritos['carrito']['count'] = $result_fin;
            if ($route_name == 'carrito') {
                $carritos['carrito']['class'] = "active";
                
            }
            
        } else {
            $items['inicio_sesion']['title'] = 'Inicio de Sesión';
            $items['inicio_sesion']['url'] = $this->generateUrl('app_login');
            if ($route_name == 'inicio_sesion') {
                $items['inicio_sesion']['class'] = "active";
            }
            $special= null;
            $carritos= null;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $items['administracion']['title'] = 'Administracion';
            $items['administracion']['url'] = $this->generateUrl('app_admin');
            if ($route_name == 'administracion') {
                $items['administracion']['class'] = "active";
            }
        }
        
        
        // $items['products']['title'] = 'Products';
        // $items['products']['url'] = $this->generateUrl('producto_index');
        // if (in_array($route_name, ['producto_index', 'producto_show', 'producto_new', 'producto_edit'])) {
        //     $items['products']['class'] = "active";
        // }

        return $this->render('menu/_main.html.twig', [
            'items' => $items,
            'specials' => $special,
            'carritos' => $carritos,
        ]);
    }

}