<?php

namespace BillarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('tipoProducto',ChoiceType::class, 
            array(
            'choices' => array(
                'Tiempo' => 'Tiempo',
                'Consumible' => 'Consumible',
                ),
            'attr' => array('class' => 'form-control'),
            )
        )
        ->add('valorCompra',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            'required'   => false,
            )
        )
        ->add('valorVenta',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('foto',null,array(
            'attr' => array(
                'class' => 'form-control',
                ),
            'required'   => false,
            )
        )
        ->add('status',ChoiceType::class, 
            array(
            'choices' => array(
                'Activo' => 1,
                'Inactivo' => 0,
                ),
            'attr' => array('class' => 'form-control'),
            )
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BillarBundle\Entity\Producto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'billarbundle_producto';
    }


}
