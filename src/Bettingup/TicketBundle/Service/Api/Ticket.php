<?php

namespace Bettingup\TicketBundle\Service\Api;

use DomainException;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\FormTypeInterface;

use Bettingup\TicketBundle\Entity\Ticket as TicketEntity,
    Bettingup\TicketBundle\Entity\AbstractTicket,

    Bettingup\TicketBundle\Form\Api\Ticket as TicketType,

    Bettingup\CoreBundle\Exception\FormNotValidException,

    Bettingup\UserBundle\Entity\User;

class Ticket
{
    private $em;
    private $formFactory;

    public function __construct(EntityManager $em, FormFactory $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function create(array $data, User $owner)
    {
        if (!isset($data['type'])) {
            throw new DomainException("type key is missing");
        }

        switch ($data['type']) {
            case 'simple':
                $ticket = $this->createSimple($data);
                break;

            case 'combine':
                $ticket = $this->createCombine($data);
                break;

            case 'system':
                $ticket = $this->createSystem($data);
                break;

            default:
                throw new DomainException;
        }

        $ticket->setUser($owner);

        $this->em->persist($ticket);
        $this->em->flush();

        return $ticket;
    }

    public function createSimple(array $data)
    {
        if (!isset($data['amount'])) {
            throw new DomainException("amount key is missing");
        }

        $ticket = new TicketEntity\Simple;
        $this->bindTicket(new TicketType\SimpleType, $ticket, $data);

        $ticket->setAmount($data['amount']);

        return $ticket;
    }

    private function bindTicket(FormTypeInterface $formType, AbstractTicket $ticket, array $data)
    {
        $form = $this->formFactory->create($formType, $ticket)->submit($data, false);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }
    }
}
