<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'required' => true,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'frontend.question_form.placeholder.name',
                    'data-validation-required-message' => 'Пожалуйста введите ваше имя.'
                )
            ))
            ->add('email', 'email', array(
                'required' => true,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'frontend.question_form.placeholder.email',
                    'data-validation-required-message' => 'Пожалуйста введите вашу электронную почту.'
                )
            ))
            ->add('question', 'textarea', array(
                'required' => true,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'frontend.question_form.placeholder.question',
                    'data-validation-required-message' => 'Пожалуйста введите вопрос.'
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
            'data_class' => 'Site\MainBundle\Form\QuestionForm',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_mainbundle_question_form';
    }
}
