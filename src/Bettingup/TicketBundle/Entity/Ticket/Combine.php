<?php

namespace Bettingup\TicketBundle\Entity\Ticket;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Bettingup\TicketBundle\Entity\AbstractTicket;

/**
 * Combine ticket
 *
 * @ORM\Entity
 */
class Combine extends AbstractTicket
{
    /**
     * @var array
     *
     * @ORM\Column(type="array")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="array")
     */
    private $options;

    public function __construct()
    {
        parent::__construct();

        $this->options = [
            'amount'    => 0,
            'totalOdds' => 0,
        ];
    }

    public function setAmount($amount)
    {
        $this->options['amount'] = $amount;

        return $this;
    }

    public function getBet()
    {
        return $this->options['amount'];
    }

    public function setTotalOdds($totalOdds)
    {
        $this->options['totalOdds'] = $totalOdds;

        return $this;
    }

    public function getTotalOdds()
    {
        return $this->options['totalOdds'];
    }
}
