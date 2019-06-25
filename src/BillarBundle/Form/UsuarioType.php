<?php

namespace BillarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nombre',null,array(
            'attr' => array(
                'class' => 'form-control'
                )
            )
        )
        ->add('login',null,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('login', TextType::class,array(
            'attr' => array(
                'class' => 'form-control'
                ),
            )
        )
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repetir Password'),
            'options' => array('attr' => array('class' => 'form-control password-field'))
            )
        )
        ->add('role',ChoiceType::class, 
            array(
            'choices' => array(
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_USER' => 'ROLE_USER',
                ),
            'attr' => array('class' => 'form-control'),
            )
        )
        ->add('telefono',null,array(
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
            'data_class' => 'BillarBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'billarbundle_usuario';
    }


}
