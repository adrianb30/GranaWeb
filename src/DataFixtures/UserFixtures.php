<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Carrito;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('abargom082@g.educaand.es');
        $user->setNombre('Adrian');
        $user->setApellidos('Barranco Gómez');
        $user->setDni('77024082A');
        $user->setDireccion('Calle Alamo');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Toor_2020'
        ));
        $carrito = new Carrito();
        $user->setCarrito($carrito);
        $manager->persist($user);

        $manager->flush();
    }
}
