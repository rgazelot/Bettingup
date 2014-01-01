<?php

namespace Bettingup\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BettingupCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
