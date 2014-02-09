<?php

namespace Bettingup\TicketBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Bettingup\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Bettingup\TicketBundle\Entity\TicketRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *      0 = "Bettingup\TicketBundle\Entity\Ticket\Simple",
 *      1 = "Bettingup\TicketBundle\Entity\Ticket\Combine",
 *      2 = "Bettingup\TicketBundle\Entity\Ticket\System"
 * })
 * @ORM\Table(name="Ticket")
 */
abstract class AbstractTicket
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotNull()
     * @Assert\DateTime
     */
    protected $createdAt;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="float")
     */
    protected $profit;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Bettingup\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var Ticket
     *
     * @ORM\OneToMany(targetEntity="Bettingup\TicketBundle\Entity\Bet", mappedBy="ticket", cascade={"persist", "remove"})
     */
    private $bets;

    public function __construct()
    {
        $this->profit    = 0.0;
        $this->createdAt = new DateTime;
        $this->bets      = new ArrayCollection;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setProfit($profit)
    {
        $this->profit = $profit;

        return $this;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function addBet(Bet $bet)
    {
        $bet->setTicket($this);
        $this->bets->add($bet);

        return $this;
    }

    public function removeBet(Bet $bet)
    {
        $this->bets->removeElement($bet);

        return $this;
    }

    public function getBets()
    {
        return $this->bets;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
