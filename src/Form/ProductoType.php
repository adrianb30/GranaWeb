<?php

namespace App\Form;

use App\Controller\ProductoController;
use App\Entity\Producto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Repository\CategoriaRepository;

class ProductoType extends AbstractType
{
    private $categoriarepository;

    public function __construct(categoriarepository $categoriarepository)
    {
        $this->categoriarepository = $categoriarepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('nombre', TextType::class,[
                'label' => 'Nombre:',
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Introduce el nombre del producto'
                ],
            ])
            ->add('descripcion', TextType::class,[
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Introduce la descripción del producto'
                ],
            ])
            ->add('stock', NumberType::class,[
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Introduce el stock'
                ],
            ])
            ->add('imagen', FileType::Class, [
                'label' => 'Imagen (JPG,PNG)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '500M',
                        
                    ])
                ],
            ])
            ->add('categoria', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => $this->categoriarepository->getcategorias(),
                'label'=>'Seleccione una categoria: ',
                'attr' => [
                    'class' => 'form-control mb-2',
                ],
            ])
            ->add('precio', NumberType::class,[
                'label'=>'Precio:',
                'attr' => [
                    'class' => 'form-control mb-2',
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
