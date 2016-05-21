<?php

namespace Site\MainBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\MainBundle\Entity\PaymentManager;

class AgentController extends Controller
{

    /**
     * Проверка всех клиентов
     *
     * @return Response
     */
    public function clientAction()
    {
        $pm = new PaymentManager($this, $this->container);

        $pm->checkAllClient();

        return new Response('OK', 200);
    }
}
