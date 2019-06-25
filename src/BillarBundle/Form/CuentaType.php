<?php

namespace BillarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CuentaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder          
        ->add('mesa', 
            EntityType::class, 
            array(
                'class' => 'BillarBundle:Mesa',
                'choice_label' => 'nombre',
                'label' => 'Mesa',
                'placeholder' => 'Seleccione una opción',
                'attr' => array('class' => 'form-control'),
            )
        )
        ->add('cliente', EntityType::class, array(
                'class' => 'BillarBundle:Cliente',
                'label' => 'Cliente',
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
        ->add('usuario', HiddenType::class)        
        //->add('fechaActualizacion', HiddenType::class)        
        //->add('fechaCierre', HiddenType::class)        
        ->add('status', HiddenType::class);        
        /*    
        ->add('idUsuario', EntityType::class, array(
                'class' => 'BillarBundle:Usuario',
                'choice_label' => 'nombre',
                'label' => 'Usuario',               
                'placeholder' => 'Seleccione una opción',
                'attr' => array('class' => 'form-control'),
                )
            )
        ->add('fechaActualizacion', 
            DateType::class, array(
                'widget' => 'single_text',
                'placeholder' => 'Seleccione una fecha',
                'attr' => array('class' => 'form-control'),
                'data'  => new \DateTime('now'),
            )
        )
        ->add('fechaCierre', 
            DateType::class, array(
                'widget' => 'single_text',
                'placeholder' => 'Seleccione una fecha',
                'attr' => array('class' => 'form-control'),
            )            
        )
        ->add('status',
            ChoiceType::class, 
            array(
                'choices' => array(
                    'Cerrada' => 0,
                    'Abierta' => 1,
                ),
                'attr' => array('class' => 'form-control'),
            )
        );
        */
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BillarBundle\Entity\Cuenta',
            'attr' => array(
                'class' => 'form-group'
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'billarbundle_cuenta';
    }


}
