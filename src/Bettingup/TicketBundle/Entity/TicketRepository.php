<?php

namespace Bettingup\TicketBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TicketRepository extends EntityRepository
{
    public function get($hash)
    {
        return $this->prepareQuery()
            ->addSelect('bets')
                ->leftJoin('ticket.bets', 'bets')
            ->where('ticket.hash = :hash')
                ->setParameter('hash', $hash)
            ->getQuery()
            ->getSingleResult();
    }

    private function prepareQuery()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('ticket')
            ->from('Bettingup\TicketBundle\Entity\AbstractTicket', 'ticket');
    }
}
