<?php

namespace Site\MainBundle\Controller\Frontend;

use GuzzleHttp\Message\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as Resp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session;

use Site\MainBundle\Entity\Payment;
use Site\MainBundle\Form\PaymentType;
use Site\MainBundle\Entity\PaymentManager;


class PaymentController extends Controller
{
    /**
     * Оплата услуги
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paymentAction(Request $request){
        $payment = new Payment();
        $form = $this->createPaymentForm($payment);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $this->get("session")->set("payment", $payment);

                if($request->isMethod('POST')){
                    $pm = new PaymentManager($this, $this->container);
                    $url = $pm->payment($payment);

                    $this->get("session")->remove('payment');

                    if($request->get('ajax')) {
                        return new JsonResponse([
                            'status' => 'OK',
                            'redirect' => $url
                        ]);
                    }

                    return $this->redirect($url);
                }

            }
        }

        $params = array(
            'form' => $form->createView()
        );

        if($request->get('ajax')) {
            return $this->render('SiteMainBundle:Frontend/Payment:paymentAjax.html.twig', $params);
        }

        return $this->render('SiteMainBundle:Frontend/Payment:payment.html.twig', $params);
    }

    /**
     * Страница успешной оплаты
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function completedAction(){
        $params = array(
        );
        return $this->render('SiteMainBundle:Frontend/Payment:completed.html.twig', $params);
    }

    /**
     * Страница неуспешной оплаты
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function failedAction(){
        $params = array(
        );
        return $this->render('SiteMainBundle:Frontend/Payment:failed.html.twig', $params);
    }

    /**
     * Проверка оплаты
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function checkoutAction(Request $request){
        $payment = $this->get("session")->get("payment");

        if(!is_object($payment)) {
            throw $this->createNotFoundException($this->get('translator')->trans('frontend.payment.error'));
        }

        if($request->isMethod('POST')){
            $pm = new PaymentManager($this, $this->container);
            $url = $pm->payment($payment);

            $this->get("session")->remove('payment');

            return $this->redirect($url);
        }

        $params = array(
            'payment' => $payment
        );

        if($request->get('ajax')) {
            return $this->render('SiteMainBundle:Frontend/Payment:checkoutAjax.html.twig', $params);
        }

        return $this->render('SiteMainBundle:Frontend/Payment:checkout.html.twig', $params);
    }

    public function checkoutRenewalAction($id){
        $repository = $this->getDoctrine()->getRepository('SiteMainBundle:Payment');
        $payment = $repository->find($id);

        if(!is_object($payment)) {
            throw $this->createNotFoundException($this->get('translator')->trans('frontend.payment.error'));
        }

        $pm = new PaymentManager($this, $this->container);
        $url = $pm->payment($payment);

        $this->get("session")->remove('payment');

        return $this->redirect($url);
    }

    /**
     * Создание формы оплаты
     *
     * @param Payment $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createPaymentForm(Payment $entity)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new PaymentType($user), $entity, array(
            'action' => $this->generateUrl('frontend_client_payment') . '?ajax=true',
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'frontend.payment.send',
            'attr' => array(
                'class' => 'btn'
            )
        ));

        return $form;
    }

    /**
     * Нотификация платежа
     *
     * @param Request $request
     * @return Resp
     */
    public function notificationAction(Request $request){
        $id = $request->get('OrderId');

        $pm = new PaymentManager($this, $this->container);
        $response = $pm->paymentNotification($id, $request);

        return $response;
    }

    /**
     * Отмена платежа
     *
     * @param $id
     * @return Resp
     */
    public function cancelAction($id){
        $pm = new PaymentManager($this, $this->container);
        $response = $pm->paymentCancel($id);

        return new Resp($response);
    }
}
