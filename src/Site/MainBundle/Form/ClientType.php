<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array(
                'required' => true,
                'label' => 'backend.client.username',
                'attr' => array(
                    'placeholder' => 'backend.client.username'
                )
            ))
            ->add('password', 'password', array(
                'required' => true,
                'label' => 'backend.client.password',
                'attr' => array(
                    'placeholder' => 'backend.client.password'
                )
            ))
            ->add('email', 'email', array(
                'required' => true,
                'label' => 'backend.client.email',
                'attr' => array(
                    'placeholder' => 'backend.client.email'
                )
            ))
            ->add('phone', null, array(
                'required' => true,
                'label' => 'backend.client.phone',
                'attr' => array(
                    'placeholder' => 'backend.client.phone'
                )
            ))
            ->add('isActive', 'choice', array(
                'required' => true,
                'label' => 'backend.client.is_active',
                'choices' => array(
                    0 => 'Да',
                    1 => 'Нет'
                )
            ))
            ->add('isPayment', 'choice', array(
                'required' => true,
                'label' => 'backend.client.is_payment',
                'choices' => array(
                    0 => 'Нет',
                    1 => 'Да'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\MainBundle\Entity\Client',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
