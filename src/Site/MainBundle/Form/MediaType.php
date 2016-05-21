<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'required' => false,
                'label' => 'backend.media.title'
            ))
            ->add('created', 'datetime', array(
                'required' => true,
                'label' => 'backend.media.created'
            ))
//            ->add('description', null, array(
//                'required' => false,
//                'label' => 'backend.media.description'
//            ))
//            ->add('file', 'file', array(
//                'required' => false,
//                'label' => 'backend.media.preview'
//            ))
            ->add('metaTitle', null, array(
                'required' => false,
                'label' => 'backend.media.metaTitle'
            ))
            ->add('metaDescription', 'textarea', array(
                'required' => false,
                'label' => 'backend.media.metaDescription'
            ))
            ->add('metaKeywords', null, array(
                'required' => false,
                'label' => 'backend.media.metaKeywords'
            ))
            ->add('videoUrl', 'url', array(
                'required' => false,
                'label' => 'backend.media.video'
            ))
            ->add('gallery', 'file', array(
                'required' => false,
                'label' => 'backend.media.photos',
                'attr' => array(
                    'class' => 'uploadify',
                    'multiple' => true
                )
            ))
            ->add('text', 'textarea', array(
                'required' => false,
                'label' => 'backend.media.text',
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
            'data_class' => 'Site\MainBundle\Entity\Media',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_mainbundle_media';
    }
}
