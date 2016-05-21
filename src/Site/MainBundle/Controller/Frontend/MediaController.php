<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    public function indexAction()
    {
        $repository_media = $this->getDoctrine()->getRepository('SiteMainBundle:Media');

        $media = $repository_media->findAllLimits(15);

        $params = array(
            'media' => $media,
            'mediaLength' => count($media),
            'lastSlug' => count($media) ? $media[count($media) - 1]->getSlug() : 0,
        );

        return $this->render('SiteMainBundle:Frontend/Media:index.html.twig', $params);
    }

    public function oneAction($slug)
    {
        $repository_media = $this->getDoctrine()->getRepository('SiteMainBundle:Media');

        $allMedia = $repository_media->findAllWithoutSlug($slug, 15);
        $media = $repository_media->findOneBySlug($slug);

        $params = array(
            'allMedia' => $allMedia,
            'mediaLength' => count($allMedia),
            'lastSlug' => count($allMedia) ? $allMedia[count($allMedia) - 1]->getSlug() : 0,
            'media' => $media
        );

        return $this->render('SiteMainBundle:Frontend/Media:one.html.twig', $params);
    }

    public function ajaxAction($slug)
    {
        $repository_media = $this->getDoctrine()->getRepository('SiteMainBundle:Media');

        $allMedia = $repository_media->findAllWithoutSlug($slug, 15);

        $params = array(
            'allMedia' => $allMedia,
            'mediaLength' => count($allMedia),
            'lastSlug' => count($allMedia) ? $allMedia[count($allMedia) - 1]->getSlug() : 0
        );

        return $this->render('SiteMainBundle:Frontend/Media:ajax.html.twig', $params);
    }

    public function getContentAction(Request $request)
    {
        $media_id = $request->get('media_id');
        $media = $this->getDoctrine()->getRepository('SiteMainBundle:Media')->find($media_id);

        if (!$media) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.media.not_found'));
        }

        return new Response($media->getText());
    }
}
