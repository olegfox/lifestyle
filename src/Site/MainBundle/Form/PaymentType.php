<?php

namespace Site\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Site\MainBundle\Entity\Client;

class PaymentType extends AbstractType
{
    /**
     * Client
     *
     * @var
     */
    private $client;

    /**
     * Container
     *
     * @param Client $client
     */
    public function __construct(Client $client){
        $this->client = $client;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $numberMonth = array();
        if(is_object($this->client)){
            $numberMonth[1] = 1;
        } else {
            for($i = 1; $i <= 12; $i++){
                $numberMonth[$i] = $i;
            }
        }


        $builder
            ->add('tariff', 'entity', array(
                'required' => true,
                'label' => 'frontend.payment.tariff',
                'class' => 'Site\MainBundle\Entity\Tariff',
                'query_builder' => function (EntityRepository $er) {
                    if(is_object($this->client)){
                        if(is_object($this->client->getTariff()) && $this->client->getDaysLeft() > 0){
                            return $er->createQueryBuilder('t')
                                ->where('t.disable = false')
                                ->andWhere('t = :tariff')
                                ->setParameter('tariff', $this->client->getTariff());
                        }
                    }

                    return $er->createQueryBuilder('t')
                        ->where('t.disable = false');
                }
            ))
            ->add('numberMonth', 'choice', array(
                'required' => true,
                'label' => 'frontend.payment.numberMonth',
                'choices' => $numberMonth
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\MainBundle\Entity\Payment',
            'translation_domain' => 'menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_mainbundle_payment';
    }
}
