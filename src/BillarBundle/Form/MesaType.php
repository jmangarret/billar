<?php

namespace BillarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MesaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre',
            null,
            array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('tipo',
            ChoiceType::class, 
            array(
                'choices' => array(
                    'Billar' => 'Billar',
                    'Karaoke' => 'Karaoke',
                    'General' => 'General',
                ),
                'attr' => array('class' => 'form-control'),
            )        
        )
        ->add('status',
            ChoiceType::class, 
            array(
                'choices' => array(
                    'Disponible' => 0,
                    'Ocupada' => 1,
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
            'data_class' => 'BillarBundle\Entity\Mesa',
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
        return 'billarbundle_mesa';
    }


}
