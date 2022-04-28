<?php

namespace App\Form;

use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class,[
                'label' => 'Nombre:',
                'help' => 'Introduce el nombre del producto'
            ])
            ->add('descripcion')
            ->add('stock')
            ->add('imagen', FileType::Class, [
                'label' => 'Imagen (JPG,PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '500M',
                        'mimeTypes' => [
                            'imagen/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Por favor seleccione un imagen valida',
                    ])
                ],
            ])
            ->add('precio', NumberType::class,[
                'label'=>'Precio:',
                'help' => 'Intoduce un valor decimal',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Precio del producto'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Producto::class,
        ]);
    }
}
