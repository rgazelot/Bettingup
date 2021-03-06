<?php

namespace Bettingup\TicketBundle\Entity\Ticket;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Bettingup\TicketBundle\Entity\AbstractTicket;

/**
 * Simple ticket
 *
 * @ORM\Entity
 */
class Simple extends AbstractTicket
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
            'amount' => 0,
        ];
    }

    public function setAmount($amount)
    {
        $this->options['amount'] = $amount;

        return $this;
    }

    public function getAmount()
    {
        return $this->options['amount'];
    }
}
