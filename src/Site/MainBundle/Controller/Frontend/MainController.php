<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $repository_tariff = $this->getDoctrine()->getRepository('SiteMainBundle:Tariff');
        $repository_background = $this->getDoctrine()->getRepository('SiteMainBundle:Background');
        $repository_page = $this->getDoctrine()->getRepository('SiteMainBundle:Page');

        $tariffs = $repository_tariff->findAll();
        $backgrounds = $repository_background->findBy(array('main' => true));
        $page = $repository_page->findOneBySlug('glavnaia');

        return $this->render('SiteMainBundle:Frontend/Main:index.html.twig', array(
            'tariffs' => $tariffs,
            'backgrounds' => $backgrounds,
            'page' => $page
        ));
    }
}
