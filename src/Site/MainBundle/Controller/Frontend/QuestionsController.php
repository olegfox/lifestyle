<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\MainBundle\Form\QuestionForm;
use Site\MainBundle\Form\QuestionFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class QuestionsController extends Controller
{
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('SiteMainBundle:Questions');

        $questions = $repository->findAll();

        if(!$questions){
            throw $this->createNotFoundException($this->get('translator')->trans('backend.questions.not_found'));
        }

        $questionFormObject = new QuestionForm();
        $questionForm = $this->createForm(new QuestionFormType(), $questionFormObject);

        if($request->isMethod('POST')){
            $questionForm->handleRequest($request);

            if($questionForm->isValid()){
                $swift = \Swift_Message::newInstance()
                    ->setSubject('Life-style-manager (Вопрос с сайта)')
                    ->setFrom(array('1991oleg22@gmail.com' => "Life-style-manager (Вопрос с сайта)"))
                    ->setTo(array('olegmitin25011986@gmail.com'))
                    ->setBody(
                        $this->renderView(
                            'SiteMainBundle:Frontend/Questions:message.html.twig',
                            array(
                                'form' => $questionFormObject
                            )
                        )
                        , 'text/html'
                    );

                $this->get('mailer')->send($swift);

                return new JsonResponse([
                    'status' => 'OK',
                    'message' => 'Ваш вопрос успешно отправлен. Мы скоро с вами свяжемся!'
                ]);
            } else {


                if ($questionForm->count()) {
                    foreach ($questionForm as $child) {
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
            'questions' => $questions,
            'form' => $questionForm->createView()
        );
        return $this->render('SiteMainBundle:Frontend/Questions:index.html.twig', $params);
    }

    public function oneAction($slug)
    {
        $repository = $this->getDoctrine()->getRepository('SiteMainBundle:Questions');

        $question = $repository->findOneBy(array('slug' => $slug));

        if(!$question){
            throw $this->createNotFoundException($this->get('translator')->trans('backend.questions.not_found'));
        }

        $params = array(
            'question' => $question
        );
        return $this->render('SiteMainBundle:Frontend/Questions:one.html.twig', $params);
    }
}
