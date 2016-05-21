<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\MainBundle\Form\Feedback;
use Site\MainBundle\Form\FeedbackType;


class FeedbackController extends Controller {

    /**
     * Форма отправки письма в тех. поддержку
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request){
        $feedback = new Feedback();
        $form = $this->createForm(new FeedbackType(), $feedback, array(
            'action' => $this->generateUrl('frontend_feedback')
        ));

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $swift = \Swift_Message::newInstance()
                    ->setSubject('Life-style-manager (Письмо с сайта)')
                    ->setFrom(array($this->container->getParameter('email_from') => "Life-style-manager (Письмо с сайта)"))
                    ->setTo($this->container->getParameter('emails_admin'))
                    ->setBody(
                        $this->renderView(
                            'SiteMainBundle:Frontend/Feedback:message.html.twig',
                            array(
                                'form' => $feedback
                            )
                        )
                        , 'text/html'
                    );

                $this->get('mailer')->send($swift);

                return new JsonResponse([
                    'status' => 'OK',
                    'notice' => 'Сообщение отправлено. Мы скоро с вами свяжемся!'
                ]);
            } else {
                if ($form->count()) {
                    foreach ($form as $child) {
                        if (!$child->isValid()) {
                            $errors[$child->getName()]['status'] = 'ERROR';
                        } else {
                            $errors[$child->getName()]['status'] = "OK";
                        }
                    }
                }

                return new JsonResponse(array_merge(['status' => 'ERROR'], $errors));
            }
        }

        $params = array(
            'form' => $form->createView()
        );

        if($request->get('ajax')) {
            return $this->render('SiteMainBundle:Frontend/Feedback:ajax.html.twig', $params);
        }

        return $this->render('SiteMainBundle:Frontend/Feedback:index.html.twig', $params);
    }

}