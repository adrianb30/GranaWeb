<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backoffice/producto")
 */
class ProductoController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_producto_index", methods={"GET"})
     */
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('producto/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_producto_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductoRepository $productoRepository): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $dirIMG */
            $dirIMG= $form['imagen']->getData();
            // $productoRepository->add($producto);
            if ($dirIMG) {
                $originalFilename = pathinfo($dirIMG->getClientOriginalName(), PATHINFO_FILENAME);
                // This is needed to safely include the file name as part of the URL
                // Enable "Intl" extension in "php.ini"
                // https://stackoverflow.com/questions/33869521/how-can-i-enable-php-extension-intl
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$dirIMG->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $dirIMG->move(
                        $this->getParameter('imagen_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // Updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $producto->setImagen($newFilename);
            }
            $productoRepository->add($producto);
            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_producto_show", methods={"GET"})
     */
    public function show(Producto $producto): Response
    {
        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_producto_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Producto $producto, ProductoRepository $productoRepository): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $imagendir */
            $imagendir = $form['imagen']->getData();

            // This condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imagendir) {
                $originalFilename = pathinfo($imagendir->getClientOriginalName(), PATHINFO_FILENAME);
                // This is needed to safely include the file name as part of the URL
                // Enable "Intl" extension in "php.ini"
                // https://stackoverflow.com/questions/33869521/how-can-i-enable-php-extension-intl
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagendir->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imagendir->move(
                        $this->getParameter('imagen_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e;
                    // ... handle exception if something happens during file upload
                }

                // Updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $producto->setImagen($newFilename);
                echo $producto->getimagen();
            }
            $productoRepository->add($producto);
            return $this->redirectToRoute('app_admin_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_producto_delete", methods={"POST"})
     */
    public function delete(Request $request, Producto $producto, ProductoRepository $productoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $productoRepository->remove($producto);
        }

        return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
    }
}
