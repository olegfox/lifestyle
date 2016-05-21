<?php

namespace Site\MainBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\MainBundle\Entity\Questions;
use Site\MainBundle\Form\QuestionsType;

/**
 * Questions controller.
 *
 */
class QuestionsController extends Controller
{

    /**
     * Lists all Questions entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteMainBundle:Questions')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $this->get('request')->query->get('questions', 1) /*questions number*/,
            10/*limit per questions*/
        );

        return $this->render('SiteMainBundle:Backend/Questions:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    /**
     * Creates a new Questions entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Questions();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_questions_show', array('id' => $entity->getId())));
        }

        return $this->render('SiteMainBundle:Backend/Questions:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Questions entity.
     *
     * @param Questions $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Questions $entity)
    {
        $form = $this->createForm(new QuestionsType(), $entity, array(
            'action' => $this->generateUrl('backend_questions_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'backend.create'));

        return $form;
    }

    /**
     * Displays a form to create a new Questions entity.
     *
     */
    public function newAction()
    {
        $entity = new Questions();
        $form   = $this->createCreateForm($entity);

        return $this->render('SiteMainBundle:Backend/Questions:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Questions entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteMainBundle:Questions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.questions.not_found'));
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteMainBundle:Backend/Questions:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Questions entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteMainBundle:Questions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.questions.not_found'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteMainBundle:Backend/Questions:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Questions entity.
    *
    * @param Questions $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Questions $entity)
    {
        $form = $this->createForm(new QuestionsType(), $entity, array(
            'action' => $this->generateUrl('backend_questions_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'backend.update'));

        return $form;
    }
    /**
     * Edits an existing Questions entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteMainBundle:Questions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.questions.not_found'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('backend_questions_show', array('id' => $id)));
        }

        return $this->render('SiteMainBundle:Backend/Questions:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Questions entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteMainBundle:Questions')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException($this->get('translator')->trans('backend.questions.not_found'));
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('backend_questions_index'));
    }

    /**
     * Creates a form to delete a Questions entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_questions_delete', array('id' => $id)))
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
