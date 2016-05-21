<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', null, array(
                'required' => false,
                'label' => 'backend.questions.position',
                'attr' => array(
                    'min' => 0
                )
            ))
            ->add('title', 'text', array(
                'required' => true,
                'label' => 'backend.questions.title'
            ))
            ->add('metaTitle', 'text', array(
                'required' => false,
                'label' => 'backend.questions.metatitle'
            ))
            ->add('metaDescription', 'textarea', array(
                'required' => false,
                'label' => 'backend.questions.metadescription'
            ))
            ->add('metaKeywords', 'text', array(
                'required' => false,
                'label' => 'backend.questions.metakeywords'
            ))
            ->add('answer', 'textarea', array(
                'required' => false,
                'label' => 'backend.questions.answer',
                "attr" => array(
                    "class" => "ckeditor"
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
            'data_class' => 'Site\MainBundle\Entity\Questions',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_mainbundle_questions';
    }
}
