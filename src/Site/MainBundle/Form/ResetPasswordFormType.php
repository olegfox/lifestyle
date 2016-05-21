<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResetPasswordFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'first_options' => array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'frontend.reset_password_form.new_password'
                )
            ),
            'second_options' => array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'frontend.reset_password_form.new_password_confirmation'
                )
            ),
            'invalid_message' => 'Пароли не совпадают',
        ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\MainBundle\Entity\ResetPassword',
            'intention'  => 'reset_password',
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
