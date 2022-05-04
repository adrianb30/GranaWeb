<?php

namespace App\Form;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Escribe tu nombre aquí',
                    'class' => 'form-control'
                )
            ))
            ->add('apellidos', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Escribe tus apellidos aqui',
                    'class' => 'form-control'
                    
                )
            ))
            ->add('dni', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Escribe tu DNI aqui',
                    'class' => 'form-control'
                )
            ))
            ->add('direccion', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Escribe tu dirección aqui',
                    'class' => 'form-control'
                )
            ))
            ->add('email', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Escribe tu Correo aquí',
                    'class' => 'form-control'
                )
            ))
            ->add('Roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                  'Cliente' => 'ROLE_USER',
                  'Administrador' => 'ROLE_ADMIN',
                ],
            ])
        ;

        $builder->get('Roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
