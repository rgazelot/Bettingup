<?php

namespace Bettingup\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ApiTicketController extends FOSRestController
{
    public function getTicketAction($id)
    {
        return $this->view($this->get('bettingup.api.user')->get($slug), 200);
    }
}
