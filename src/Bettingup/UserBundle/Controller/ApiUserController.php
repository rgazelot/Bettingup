<?php

namespace Bettingup\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ApiUserController extends FOSRestController
{
    public function getUserAction($slug)
    {
        return $this->view($this->get('bettingup.api.user')->get($slug), 200);
    }
}
