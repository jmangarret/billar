<?php

namespace BillarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ClienteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('tipoDocumento',
            ChoiceType::class, 
            array(
                'choices' => array(
                    'Cedula de Ciudadania' => 'CC',
                    'Cedula de Extranjeria' => 'CE',
                    'Pasaporte' => 'PP',
                    'RUT/NIT' => 'RN',
                ),
                'attr' => array('class' => 'form-control'),
            )
        )
        ->add('documento',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('nombre',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('direccion',TextareaType::class, array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('telefono',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('status',
            ChoiceType::class, 
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
            'data_class' => 'BillarBundle\Entity\Cliente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'billarbundle_cliente';
    }


}
