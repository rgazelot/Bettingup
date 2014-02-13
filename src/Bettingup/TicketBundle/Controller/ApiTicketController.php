<?php

namespace Bettingup\TicketBundle\Controller;

use DomainException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Request,

    Symfony\Component\HttpKernel\Exception\BadRequestHttpException,
    Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;

use Bettingup\CoreBundle\Exception\FormNotValidException,

    Bettingup\TicketBundle\Exception\TicketNotFoundException;

class ApiTicketController extends FOSRestController
{
    public function getTicketsAction(Request $request)
    {
        if (!$request->query->has('user')) {
            throw new BadRequestHttpException('user parameter missing');
        }

        if ($this->getUser()->getHash() !== $request->query->get('user')) {
            throw new AccessDeniedHttpException("Wrong rights");
        }

        return $this->view($this->get('bettingup.api.ticket')->all($request->query->get('user')), 200);
    }

    public function getTicketAction(Request $request, $hash)
    {
        $ticket = $this->get('bettingup.api.ticket')->get($hash);

        if (!$ticket->getUser()->isEqualTo($this->getUser())) {
            throw new TicketNotFoundException;
        }

        return $this->view($ticket, 200);
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

    public function deleteTicketAction(Request $request, $hash)
    {
        $apiTicket = $this->get('bettingup.api.ticket');
        $ticket    = $apiTicket->get($hash);

        if (!$ticket->getUser()->isEqualTo($this->getUser())) {
            throw new AccessDeniedHttpException("Wrong rights");
        }

        $apiTicket->delete($ticket);

        return $this->view(null, 204);
    }
}
