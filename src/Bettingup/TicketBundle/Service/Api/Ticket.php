<?php

namespace Bettingup\TicketBundle\Service\Api;

use DomainException;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\FormTypeInterface;

use Bettingup\TicketBundle\Entity\Ticket as TicketEntity,
    Bettingup\TicketBundle\Entity\AbstractTicket,
    Bettingup\TicketBundle\Entity\Bet,
    Bettingup\TicketBundle\Exception\InvalidSetOfBetsException,
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

    /**
     * Create a Ticket and its Bets
     * 
     * @param  array  $data  
     * @param  User   $owner 
     * 
     * @return AbstractTicket
     */
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

    /**
     * Create a simple Ticket
     * 
     * @param  array  $data
     *
     * @throws DomainException           If amount key doesn't exists
     * @throws InvalidSetOfBetsException If there is more than one bet
     * 
     * @return Simple
     */
    private function createSimple(array $data)
    {
        if (!isset($data['amount'])) {
            throw new DomainException("amount key is missing");
        }

        $ticket = new TicketEntity\Simple;
        $this->bindTicket(new TicketType\SimpleType, $ticket, $data);

        if ($ticket->getBets()->count() !== 1) {
            throw new InvalidSetOfBetsException('A simple ticket must have only one bet.');
        }

        $ticket->setAmount($data['amount']);

        return $ticket;
    }

    /**
     * Create Combine
     * 
     * @param  array  $data
     * 
     * @return Combine
     */
    private function createCombine(array $data)
    {
        if (!isset($data['amount'])) {
            throw new DomainException("amount key is missing");
        }

        $ticket = new TicketEntity\Combine;
        $this->bindTicket(new TicketType\CombineType, $ticket, $data);

        if ($ticket->getBets()->count() <= 1) {
            throw new InvalidSetOfBetsException('A combine ticket must have at least two bets.');
        }

        $ticket->setAmount($data['amount']);
        
        $totalOdds = 1;
        $ticket->getBets()->map(function(Bet $bet) use (&$totalOdds) {
            $totalOdds = round($bet->getOdds() * $totalOdds, 2);
        });
        $ticket->setTotalOdds($totalOdds);

        return $ticket;
    }

    private function createSystem(array $data)
    {

    }

    private function bindTicket(FormTypeInterface $formType, AbstractTicket $ticket, array $data)
    {
        $form = $this->formFactory->create($formType, $ticket)->submit($data, false);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }
    }
}
