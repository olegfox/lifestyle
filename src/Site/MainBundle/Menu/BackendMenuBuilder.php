<?php

namespace Site\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class BackendMenuBuilder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->setCurrent($this->container->get('request')->getRequestUri());

        $menu->addChild('Фон на главной', array('route' => 'backend_background_index'));
        $menu->addChild('Страницы', array('route' => 'backend_page_index'));
        $menu->addChild('Тарифы', array('route' => 'backend_tariff_index'));
        $menu->addChild('Клиенты', array('route' => 'backend_client_index'));
        $menu->addChild('Блог', array('route' => 'backend_media_index'));
        $menu->addChild('Вопросы и ответы', array('route' => 'backend_questions_index'));

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Выход', array('route' => 'fos_user_security_logout'));

        $menu->setCurrent($this->container->get('request')->getRequestUri());

        return $menu;
    }
}