<?php

namespace Site\MainBundle\Entity;

use Symfony\Component\HttpFoundation\Response;
use Site\MainBundle\Entity\Payment;
use Site\MainBundle\Entity\PaymentCancel;
use Site\MainBundle\Controller\Frontend\TinkoffMerchantAPI;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

class PaymentManager
{
	protected $container;
	protected $controller;
	protected $em;
	protected $doctrine;
	
	public function __construct($controller, $container)
	{
		$this->container = $container;
		$this->controller = $controller;
		$this->em = $controller->getDoctrine()->getManager();
		$this->doctrine = $controller->getDoctrine();
	}
	
	protected function getRepository()
	{
		return $this->doctrine->getRepository('SiteMainBundle:Payment');
	}

    /**
     * Инициализация платежа
     *
     * @param Payment $payment
     * @return mixed
     */
	public function payment(Payment $payment)
	{
        $tma = new TinkoffMerchantAPI($this->container->getParameter('terminalKey'), $this->container->getParameter('terminalPassword'), $this->container->getParameter('api_url'));
        $repositoryPayment = $this->doctrine->getRepository('SiteMainBundle:Tariff');
        $tariff = $repositoryPayment->findOneByName($payment->getTariff()->getName());
        $payment->setTariff($tariff);
        $client = $this->controller->get('security.context')->getToken()->getUser();
        $payment->setClient($client);

        $client->setTariff($tariff);

		$this->em->persist($payment);
		$this->em->flush();

        $response = (array)json_decode($tma->init(['OrderId' => $payment->getId(), 'Amount' => $payment->getAmount() * 100]));

        return $response['PaymentURL'];
	}

    /**
     * Отмена платежа
     *
     * @param $paymentId
     * @return bool
     */
    public function paymentCancel($paymentId){
        $tma = new TinkoffMerchantAPI($this->container->getParameter('terminalKey'), $this->container->getParameter('terminalPassword'), $this->container->getParameter('api_url'));
        $repositoryPayment = $this->doctrine->getRepository('SiteMainBundle:Payment');
        $payment = $repositoryPayment->find($paymentId);

        if($payment){
            $response = $tma->cancel(['PaymentId' => $payment->getPaymentId()]);
            $responseDecode = (array)json_decode($response);

            $em = $this->em;
            $paymentCancel = new PaymentCancel();
            $paymentCancel->setPayment($payment);
            $paymentCancel->setDetails($responseDecode['Details']);
            $paymentCancel->setErrorCode($responseDecode['ErrorCode']);
            $paymentCancel->setMessage($responseDecode['Message']);
            $paymentCancel->setNewAmount($responseDecode['NewAmount']);
            $paymentCancel->setOrderId($responseDecode['OrderId']);
            $paymentCancel->setOriginalAmount($responseDecode['OriginalAmount']);
            $paymentCancel->setPaymentId($responseDecode['PaymentId']);
            $paymentCancel->setStatus($responseDecode['Status']);
            $paymentCancel->setSuccess($responseDecode['Success']);
            $paymentCancel->setTerminalKey($responseDecode['TerminalKey']);
            $em->persist($paymentCancel);
            $em->flush();

            return $response;
        }

        return false;
    }

    /**
     * Нотификация платежа
     *
     * @param $paymentId
     * @param $request
     * @return Response
     */
    public function paymentNotification($paymentId, $request){
        $em = $this->em;
        $repository = $this->doctrine->getRepository('SiteMainBundle:Payment');
        $payment = $repository->find($paymentId);

//      Если заказ найден, идентификатор терминала верен и сумма платежа верна
        if($payment && $request->get('TerminalKey') == $this->container->getParameter('terminalKey')){
            if($payment->getAmount() == $request->get('Amount')/100){
                $payment->setPaymentId($request->get('PaymentId'));
                $payment->setSuccess($request->get('Success'));
                $payment->setStatus($request->get('Status'));
                $payment->setErrorCode($request->get('ErrorCode'));
                $payment->setRebillId($request->get('RebillId'));
                $payment->setCardId($request->get('CardId'));
                $payment->setPan($request->get('Pan'));
                $payment->setToken($request->get('Token'));

                if($request->get('Status') == "CONFIRMED" || $request->get('Status') == "AUTHORIZED"){
                    $client = $payment->getClient();
                    $client->setIsPayment(true);

//                  Отправляем уведомление о новой оплате
                    $swift = \Swift_Message::newInstance()
                        ->setSubject('Life-style-manager (Новая оплата)')
                        ->setFrom(array($this->container->getParameter('email_from') => "Life-style-manager (Новая оплата)"))
                        ->setTo($this->container->getParameter('emails_admin'))
                        ->setBody(
                            $this->renderView(
                                'SiteMainBundle:Frontend/Feedback:newPaymentMessage.html.twig',
                                array(
                                    'form' => $client,
                                    'payment' => $payment
                                )
                            )
                            , 'text/html'
                        );

                    $this->get('mailer')->send($swift);

                    if(is_null($client->getEnded())){
                        $end = new \DateTime();
                        $end->add(new \DateInterval('P' . $payment->getNumberMonth() . 'M'));
                        $client->setEnded($end);
                    }else{
                        $end = clone $client->getEnded();
                        $end->add(new \DateInterval('P' . $payment->getNumberMonth() . 'M'));
                        $client->setEnded($end);
                    }
                }

                $em->flush();

                return new Response('OK');
            }
        }

        return new Response('Error', 500);
    }

    /**
     * Проверка всех пользователей на просрочку оплаты
     *
     * @return bool
     */
    public function checkAllClient(){
        $em = $this->em;
        $repository_client = $this->doctrine->getRepository('SiteMainBundle:Client');

        $clients = $repository_client->findAll();

        foreach($clients as $client){
            if($client->getEnded() < (new \DateTime())){
                $client->setEnded(null);
                $client->setIsPayment(false);
                $client->setTariff(null);
            }
        }

        $em->flush();

        return true;
    }
}


