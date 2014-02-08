<?php

namespace Bettingup\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Bettingup\TicketBundle\Entity\Ticket,
    Bettingup\TicketBundle\Entity\Bet;

class ApiTicketController extends FOSRestController
{
    public function getTicketAction($id)
    {
        $bet = new Bet;
        $simple = (new Ticket\Simple)
            ->addBet((new Bet))
            ->addBet((new Bet))
            ->addBet(($bet));
        var_dump(count($simple->getBets()));
        $simple->removeBet($bet);
        die(var_dump($simple));
        return $this->view($this->get('bettingup.api.user')->get($slug), 200);
    }
}
