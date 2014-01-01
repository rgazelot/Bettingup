<?php

namespace Acme\DemoBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;

class FOSController extends FOSRestController
{
    public function getUsersAction()
    {
        $data = ['foo', 'bar', 'baz'];
        $view = $this->view($data, 200);

        return $view;
    }

    public function getUserAction($slug)
    {
        die(var_dump($slug));
    }

    public function postUsersAction(Request $request)
    {
        die(var_dump($request->request->all()));
    }
}
