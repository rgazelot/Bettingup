<?php

namespace Bettingup\TicketBundle\Entity\Ticket;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Bettingup\TicketBundle\Entity\AbstractTicket;

/**
 * System ticket
 *
 * @ORM\Entity
 */
class System extends AbstractTicket
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

    public function __construct($type)
    {
        parent::__construct();

        $this->options = [
            'amountPerBets' => 0,
        ];

        $this->setSystemType($type);
    }

    public function setAmountPerBets($amountPerBets)
    {
        $this->options['amountPerBets'] = $amountPerBets;

        return $this;
    }

    public function getAmountPerBets()
    {
        return $this->options['amountPerBets'];
    }

    /**
     * Set system type
     *
     * @param int $type The key of the system stored in System::getSystemeType
     *
     * @throws OutOfBoundsException If the type doesn't exist
     */
    public function setSystemType($type)
    {
        if (!isset(self::getSystemeType()[$type])) {
            throw new OutOfBoundsException('Incorrect system type', 400);
        }

        $this->options['systemType'] = $type;

        return $this;
    }

    public static function getSystemeType()
    {
        return [
            '2_3',
            '2_4',
            '3_4',
            '2_5',
            '3_5',
            '4_5',
            '2_6',
            '3_6',
            '4_6',
            '5_6',
            '2_7',
            '3_7',
            '4_7',
            '5_7',
            '6_7',
        ];
    }
}
