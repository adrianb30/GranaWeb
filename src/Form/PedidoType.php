<?php

namespace App\Form;
use App\Repository\UserRepository;
use App\Entity\Pedido;
use App\Entity\DetallePedido;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PedidoType extends AbstractType
{
    public function __construct(UserRepository $UserRepository)
    {
        $this->userrepository = $UserRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('estado')
            ->add('transaccionid')
            ->add('usuario', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => $this->userrepository->getUsers(),
                'label'=>'Seleccione a un cliente: ',
                'attr' => [
                    'class' => 'form-control mb-2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pedido::class,
        ]);
    }
}
