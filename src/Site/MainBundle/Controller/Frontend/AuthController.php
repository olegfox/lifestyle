<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Site\MainBundle\Entity\Client;
use Site\MainBundle\Entity\ChangePassword;
use Site\MainBundle\Entity\ForgetPassword;
use Site\MainBundle\Entity\ResetPassword;
use Site\MainBundle\Form\ClientType;
use Site\MainBundle\Form\ChangePasswordFormType;
use Site\MainBundle\Form\ForgetPasswordFormType;
use Site\MainBundle\Form\ResetPasswordFormType;

class AuthController extends Controller
{
    /**
     * Аутентификация пользователя
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $xhr = $request->get('xhr') || 0;

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $repository_background = $this->getDoctrine()->getRepository('SiteMainBundle:Background');
        $backgrounds = $repository_background->findBy(array('main' => true));

//        die(var_dump($error));

        if ($xhr)
        {

            return $this->render(
                'SiteMainBundle:Frontend/Auth:login.html.twig',
                array(
                    'last_username' => $lastUsername,
                    'error'         => $error,
                    'backgrounds' => $backgrounds
                )
            );

        }
        else
        {

            return $this->redirect($this->generateUrl('frontend_homepage') . '?auth=true', 302);

        }

    }

    /**
     * Личный кабинет
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function roomAction(Request $request){
        $client = $this->get('security.context')->getToken()->getUser();

        if(!is_object($client->getTariff())){

            if($request->get('ajax')) {

                return new JsonResponse([
                    'status' => 'OK',
                    'redirect' => $this->generateUrl('frontend_client_payment')
                ]);

            }

            return $this->redirect($this->generateUrl('frontend_client_payment'));
        }

        $payments = $client->getPayments();

        $params = array(
            'payments' => $payments
        );

        if($request->get('ajax')) {

            return new JsonResponse([
                'status' => 'OK',
                'url_addr' => $this->generateUrl('frontend_client_room')
            ]);

        }

        return $this->render('SiteMainBundle:Frontend/Auth:room.html.twig', $params);
    }

    /**
     * Регистрация нового клиента
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request){
        $client = new Client();
        $form = $this->createRegisterForm($client);
        $form->handleRequest($request);

        $repository = $this->getDoctrine()->getRepository('SiteMainBundle:Page');

        $page = $repository->findOneBySlug('doghovor-ofierta');

        if($request->isMethod('POST')){
            if ($form->isValid()) {
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($client);

                $password = $encoder->encodePassword($client->getPassword(), $client->getSalt());
                $client->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($client);
                $em->flush();

//              Программная аутентификация пользователя
                $token = new UsernamePasswordToken($client, null, "default", $client->getRoles());
                $this->get("security.context")->setToken($token); //now the user is logged in

                //now dispatch the login event
                $request = $this->get("request");
                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

//              Отправляем уведомление о новом клиенте
                $swift = \Swift_Message::newInstance()
                    ->setSubject('Life-style-manager (Новый клиент)')
                    ->setFrom(array($this->container->getParameter('email_from') => "Life-style-manager (Новый клиент)"))
                    ->setTo($this->container->getParameter('emails_admin'))
                    ->setBody(
                        $this->renderView(
                            'SiteMainBundle:Frontend/Feedback:newClientMessage.html.twig',
                            array(
                                'form' => $client
                            )
                        )
                        , 'text/html'
                    );

                $this->get('mailer')->send($swift);

                return new JsonResponse([
                    'status' => 'OK',
                    'redirect' => $this->generateUrl('frontend_client_payment')
                ]);
            } else {
                if ($form->count()) {
                    foreach ($form as $child) {
                        if (!$child->isValid()) {
                            $errors[$child->getName()]['status'] = 'ERROR';
                            $errors[$child->getName()]['message'] = $child->getErrors()[0]->getMessage();
                        } else {
                            $errors[$child->getName()]['status'] = "OK";
                        }
                    }
                }

                return new JsonResponse(array_merge(['status' => 'ERROR'], $errors));
            }

        }


        return $this->render('SiteMainBundle:Frontend/Auth:register.html.twig', array(
            'client' => $client,
            'form'   => $form->createView()
        ));
    }

    /**
     * Создание формы для регистрации
     *
     * @param Client $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createRegisterForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('frontend_client_register'),
            'method' => 'POST',
        ));

        $form->remove('isActive');
        $form->remove('isPayment');

        $form->add('submit', 'submit', array(
            'label' => 'frontend.register',
            'attr' => array(
                'class' => 'btn'
            )
        ));

        return $form;
    }

    /**
     * Изменение пароля
     *
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        $token = $securityContext->getToken();
        $client = $token->getUser();

        $changePassword = new ChangePassword();
        $form = $this->createForm(new ChangePasswordFormType(), $changePassword, array(
            'action' => $this->generateUrl('frontend_client_change_password')
        ));

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($client);

                $password = $encoder->encodePassword($changePassword->new, $client->getSalt());
                $client->setPassword($password);
                $em->flush();

                return new JsonResponse([
                    'status' => 'OK',
                    'notice' => 'Пароль успешно изменен!'
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
        return $this->render('SiteMainBundle:Frontend/Auth:changePassword.html.twig', $params);
    }

    /**
     * Забыли пароль
     *
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgetPasswordAction(Request $request)
    {
        $forgetPassword = new ForgetPassword();
        $form = $this->createForm(new ForgetPasswordFormType(), $forgetPassword, array(
            'action' => $this->generateUrl('frontend_client_forget_password')
        ));

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){

                $repository_client = $this->getDoctrine()->getRepository('SiteMainBundle:Client');

                $client = $repository_client->findOneBy(array('email' => $forgetPassword->getEmail()));

                if($client) {

                    // Если прошло более двух часов с момента последнего запроса
                    if(is_object($client->getKeyDate())) {
                        if((int)date_diff(new \DateTime(), $client->getKeyDate())->format('%H') < 2) {
                            return new JsonResponse([
                                'status' => 'ERROR',
                                'notice' => 'На ваш email адрес уже было отправлено письмо со ссылкой для восстановления пароля!'
                            ]);
                        }
                    }

                    $uniqLink = sha1(uniqid(mt_rand(), true));

                    $hashUniqLink = sha1($uniqLink);
                    $client->setKey($hashUniqLink);
                    $client->setKeyDate(new \DateTime());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($client);
                    $em->flush();

                    // Посылаем письмо со ссылкой для восстановления пароля
                    $swift = \Swift_Message::newInstance()
                        ->setSubject('Life-style-manager (Восстановление пароля)')
                        ->setFrom(array($this->container->getParameter('email_from') => "Life-style-manager (Восстановление пароля)"))
                        ->setTo($client->getEmail())
                        ->setBody(
                            $this->renderView(
                                'SiteMainBundle:Frontend/Auth:resetPasswordMessage.html.twig',
                                array(
                                    'client' => $client,
                                    'uniqLink' => $uniqLink
                                )
                            )
                            , 'text/html'
                        );

                    $this->get('mailer')->send($swift);

                    return new JsonResponse([
                        'status' => 'OK',
                        'notice' => 'На ваш email адрес отправлена ссылка для восстановления пароля!'
                    ]);

                }

                return new JsonResponse([
                    'status' => 'ERROR',
                    'notice' => 'Данный email адрес не найден!'
                ]);
            } else {
                if ($form->count()) {
                    foreach ($form as $child) {
                        if (!$child->isValid()) {
                            $errors[$child->getName()]['status'] = 'ERROR';
                            $errors[$child->getName()]['message'] = $child->getErrors()[0]->getMessage();
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
        return $this->render('SiteMainBundle:Frontend/Auth:forgetPassword.html.twig', $params);
    }

    /**
     * Сброс пароля
     *
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetPasswordAction(Request $request)
    {
        $key = $request->get('key');

        $repository_client = $this->getDoctrine()->getRepository('SiteMainBundle:Client');

        $client = $repository_client->findOneBy(array('key' => sha1($key)));

        if($client) {
            $resetPassword = new ResetPassword();
            $form = $this->createForm(new ResetPasswordFormType(), $resetPassword, array(
                'action' => $this->generateUrl('frontend_client_reset_password') . '?key=' . $key
            ));

            if($request->isMethod('POST')){
                $form->handleRequest($request);

                if($form->isValid()){
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($client);

                    $password = $encoder->encodePassword($client->getPassword(), $client->getSalt());
                    $client->setPassword($password);
                    $client->setKey(null);
                    $client->setKeyDate(null);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($client);
                    $em->flush();

//                  Программная аутентификация пользователя
                    $token = new UsernamePasswordToken($client, null, "default", $client->getRoles());
                    $this->get("security.context")->setToken($token); //now the user is logged in

                    //now dispatch the login event
                    $request = $this->get("request");
                    $event = new InteractiveLoginEvent($request, $token);
                    $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                    return new JsonResponse([
                        'status' => 'OK',
                        'redirect' => $this->generateUrl('frontend_homepage')
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
            return $this->render('SiteMainBundle:Frontend/Auth:resetPassword.html.twig', $params);
        } else {
            throw $this->createNotFoundException($this->get('translator')->trans('backend.page.not_found'));
        }

    }

    /**
     * Измение отдельных полей у пользователя
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateFieldAction(Request $request){
        $securityContext = $this->container->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();

        $em = $this->getDoctrine()->getManager();

        if($email = $request->get('email')){
            $user->setEmail($email);
            $validator = $this->get('validator');
            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return new JsonResponse(['status' => 'ERROR', 'message' => $errors->get(0)->getMessage()]);
            }

            $em->flush();

            return new JsonResponse([
                'status' => 'OK'
            ]);
        } elseif($phone = $request->get('phone')){
            $user->setPhone($phone);
            $em->flush();

            return new JsonResponse([
                'status' => 'OK'
            ]);
        }

        return new JsonResponse([
            'status' => 'ERROR'
        ]);
    }
}
