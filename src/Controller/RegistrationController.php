<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\UserRepository;
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $user->setRoles(["user"]);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if ($form->get('plainPassword')->getData() != $form->get('confirmpassword')->getData()) {
                $mensaje="La contraseñas no coinciden";
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'mensaje' => $mensaje
                ]);
            }
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
            // return $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'mensaje' => null
        ]);
    }

    /**
     * @Route("/perfil", name="app_perfil", methods={"GET", "POST"})
     */
    public function perfil(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user= new User();
        $email = $request->getSession()->get('_security.last_username');
        $user = $userRepository->findOneBy([
            'email' => $email
        ]);
        $mensaje = null;
        if (isset($_POST['btn_actualizar'])) {
            
            $user->setnombre($_POST['nombre']);
            $user->setapellidos($_POST['apellidos']);
            $user->setemail($_POST['email']);
            $user->setdni($_POST['dni']);
            $user->setdireccion($_POST['direccion']);
            $entityManager->persist($user);
            $entityManager->flush();
            $mensaje="Se han actualizado los datos correctamente.";
        }
        return $this->render('registration/perfil.html.twig', [
            'informacion' => $user,
            'mensaje' =>$mensaje
        ]);
    }
}
