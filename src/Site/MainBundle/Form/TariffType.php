<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TariffType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'required' => false,
                'label' => 'backend.tariff.name'
            ))
            ->add('description', 'textarea', array(
                'required' => false,
                'label' => 'backend.tariff.description',
                "attr" => array(
                    "class" => "ckeditor"
                )
            ))
            ->add('price', null, array(
                'required' => false,
                'label' => 'backend.tariff.price'
            ))
            ->add('disable', 'choice', array(
                'required' => true,
                'label' => 'backend.tariff.disable',
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
            'data_class' => 'Site\MainBundle\Entity\Tariff',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_mainbundle_tariff';
    }
}
