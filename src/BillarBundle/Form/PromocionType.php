<?php

namespace BillarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class PromocionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('producto', 
            EntityType::class, 
            array(
                'class' => 'BillarBundle:Producto',
                'choice_label' => 'nombre',
                'placeholder' => 'Seleccione una opción',
                'attr' => array('class' => 'form-control'),
            )
        )
        ->add('fechaCreacion', 
            DateType::class, array(
                'widget' => 'single_text',
                'placeholder' => 'Seleccione una fecha',
                'attr' => array('class' => 'form-control'),
                'data'  => new \DateTime('now'),
            )
        )  
        ->add('hora_desde',
            TimeType::class, array(
                'input'  => 'string',
                'widget' => 'choice',
                'attr' => array('class' => 'form-control'),
            )
        )
        ->add('hora_hasta',
            TimeType::class, array(
                'input'  => 'string',
                'widget' => 'choice',
                'attr' => array('class' => 'form-control'),
            )
        )
        ->add('valorVenta',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
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
            'data_class' => 'BillarBundle\Entity\Promocion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'billarbundle_promocion';
    }


}
