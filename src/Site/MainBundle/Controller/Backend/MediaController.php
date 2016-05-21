<?php

namespace Site\MainBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\MainBundle\Entity\Media;
use Site\MainBundle\Entity\MediaVideo;
use Site\MainBundle\Entity\MediaPhoto;
use Site\MainBundle\Form\MediaType;
use Site\MainBundle\VideoParser\VideoParser;

/**
 * Media controller.
 *
 */
class MediaController extends Controller
{

    /**
     * Lists all Media entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteMainBundle:Media')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('page', 1) /*page number*/,
            10/*limit per page*/
        );

        return $this->render('SiteMainBundle:Backend/Media:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    /**
     * Creates a new Media entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Media();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

//          Добавляеем видео
            if($entity->getVideoUrl()){

                $video = VideoParser::getVideo($entity->getVideoUrl());

                $mediaVideo = new MediaVideo();
                $mediaVideo->setTitle($video->title);
                $mediaVideo->setLink($entity->getVideoUrl());
                $mediaVideo->setOriginal($entity->getVideoUrl());
                $mediaVideo->setPreviewImageUrl($video->thumbnail_url);
                $mediaVideo->setHtml($video->html);
                $mediaVideo->setMedia($entity);

                $em->persist($mediaVideo);

            }

//          Добавляем фотки в фотогалерею
            $imagesJson = $request->get('gallery');
            if ($imagesJson) {

                $images = json_decode($imagesJson);

                foreach ($images as $image) {
                    $mediaPhoto = new MediaPhoto();
                    $mediaPhoto->setLink("uploads/media/" . $image);
                    $mediaPhoto->setMedia($entity);
                    $em->persist($mediaPhoto);
                }

            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_media_show', array('id' => $entity->getId())));
        }

        return $this->render('SiteMainBundle:Backend/Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Media entity.
     *
     * @param Media $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Media $entity)
    {
        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('backend_media_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'backend.create'));

        return $form;
    }

    /**
     * Displays a form to create a new Media entity.
     *
     */
    public function newAction()
    {
        $entity = new Media();
        $form   = $this->createCreateForm($entity);

        return $this->render('SiteMainBundle:Backend/Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Media entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteMainBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.media.not_found'));
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteMainBundle:Backend/Media:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Media entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteMainBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.media.not_found'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteMainBundle:Backend/Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Media entity.
    *
    * @param Media $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Media $entity)
    {
//      Если у поста есть видео, то добавляем в строку с видео url видео
        if(is_object($entity->getVideo())){
            $entity->setVideoUrl($entity->getVideo()->getLink());
        }

        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('backend_media_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'backend.update'));

        return $form;
    }
    /**
     * Edits an existing Media entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteMainBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.media.not_found'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

//          Редактируем видео

//          Если строка с видео не пустая
            if($entity->getVideoUrl()){

                $flag = 0;

//              Если у поста уже есть видео
                if(is_object($entity->getVideo())){
//                  Если добавляется то же видео
                    if($entity->getVideoUrl() == $entity->getVideo()->getLink()){
                        $flag = 1;
                    }
                }

                if($flag == 0){
                    $video = VideoParser::getVideo($entity->getVideoUrl());

                    $mediaVideo = new MediaVideo();

                    if(is_object($entity->getVideo())){
                        $mediaVideo = $entity->getVideo();
                    }

                    $mediaVideo->setTitle($video->title);
                    $mediaVideo->setLink($entity->getVideoUrl());
                    $mediaVideo->setOriginal($entity->getVideoUrl());
                    $mediaVideo->setPreviewImageUrl($video->thumbnail_url);
                    $mediaVideo->setHtml($video->html);
                    $mediaVideo->setMedia($entity);

                    if(!is_object($entity->getVideo())){
                        $em->persist($mediaVideo);
                    }
                }

//          Если строка с видео пустая, то удаляем видео, если оно существует
            }else{

                if(is_object($entity->getVideo())){

                    $em->remove($entity->getVideo());

                }

            }


//          Добавляем фотки в фотогалерею
            $imagesJson = $request->get('gallery');
            if ($imagesJson) {

                $images = json_decode($imagesJson);

                foreach ($images as $image) {
                    $mediaPhoto = new MediaPhoto();
                    $mediaPhoto->setLink("uploads/media/" . $image);
                    $mediaPhoto->setMedia($entity);
                    $em->persist($mediaPhoto);
                }

            }

//          Удаляем фотки из галареи, отмеченные на удаление
            $photos = $request->get('photos');

            if(is_array($photos)){
                foreach($photos as $photo){
                    $repository_media_photo = $this->getDoctrine()->getRepository('SiteMainBundle:MediaPhoto');
                    $photoObject = $repository_media_photo->find($photo);

                    if($photoObject){
                        unlink($photoObject->getLink());
                        $em->remove($photoObject);
                    }
                }
            }

            $em->flush();

            return $this->redirect($this->generateUrl('backend_media_edit', array('id' => $id)));
        }

        return $this->render('SiteMainBundle:Backend/Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Media entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteMainBundle:Media')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('backend.media.not_found'));
            }

            $entity->deleteAllPhotos();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('backend_media_index'));
    }

    /**
     * Creates a form to delete a Media entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_media_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
                'label' => 'backend.delete',
                'translation_domain' => 'menu',
                'attr' => array(
                    'class' => 'btn-danger'
                )
            ))
            ->getForm()
        ;
    }
}
