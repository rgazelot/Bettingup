<?php

namespace Bettingup\TicketBundle\Controller;

use DomainException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,

    Symfony\Component\HttpKernel\Exception\BadRequestHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use Bettingup\TicketBundle\Entity\Ticket,
    Bettingup\TicketBundle\Entity\Bet,
    Bettingup\TicketBundle\Form\Api\Ticket\SimpleType,

    Bettingup\CoreBundle\Exception\FormNotValidException;

class ApiTicketController extends FOSRestController
{
    public function getTicketAction(Request $request, $id)
    {
        try {
            return $this->view($this->get('bettingup.api.ticket')->get($id), 200);
        } catch (TicketNotFoundException $e) {}
    }

    public function postTicketAction(Request $request)
    {
        try {
            $ticket = $this->get('bettingup.api.ticket')->create($request->request->all(), $this->getUser());

            return $this->view($ticket, 201);
        } catch (DomainException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (FormNotValidException $e) {
            throw new BadRequestHttpException($e->getErrors());
        }
    }

    public function deleteTicketAction(Request $request, $id)
    {
        try {
            $ticket = $this->get('bettingup.api.ticket')->get($id);

            die(var_dump($ticket));
        } catch (TicketNotFoundException $e) {
            throw new NotFoundHttpException;
            
        }
    }
}
