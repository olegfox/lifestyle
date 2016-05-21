<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', 'entity', array(
                'required' => false,
                'label' => 'backend.page.parent',
                'class' => 'Site\MainBundle\Entity\Page',
            ))
            ->add('position', null, array(
                'required' => false,
                'label' => 'backend.page.position',
                'attr' => array(
                    'min' => 0
                )
            ))
            ->add('title', 'text', array(
                'required' => true,
                'label' => 'backend.page.title'
            ))
            ->add('metaTitle', 'text', array(
                'required' => false,
                'label' => 'backend.page.metatitle'
            ))
            ->add('metaDescription', 'textarea', array(
                'required' => false,
                'label' => 'backend.page.metadescription'
            ))
            ->add('metaKeywords', 'text', array(
                'required' => false,
                'label' => 'backend.page.metakeywords'
            ))
            ->add('text', 'textarea', array(
                'required' => false,
                'label' => 'backend.page.text',
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
            'data_class' => 'Site\MainBundle\Entity\Page',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_mainbundle_page';
    }
}
