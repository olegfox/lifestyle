<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\MainBundle\Entity\Player;

class PageController extends Controller
{
    public function indexAction($parent, $slug)
    {
        $repository = $this->getDoctrine()->getRepository('SiteMainBundle:Page');

        $page = $repository->findOneBySlug($slug);

        if(!$page){
            throw $this->createNotFoundException($this->get('translator')->trans('backend.page.not_found'));
        }

        $params = array(
            'page' => $page
        );
        return $this->render('SiteMainBundle:Frontend/Page:index.html.twig', $params);
    }
}
