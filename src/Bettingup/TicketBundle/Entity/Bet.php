<?php

namespace Bettingup\TicketBundle\Entity;

use OutOfBoundsException;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Bettingup\CoreBundle\Traits\Entity\HashTrait;

/**
 * @ORM\Entity(repositoryClass="Bettingup\TicketBundle\Entity\BetRepository")
 * @ORM\Table(name="Bet")
 */
class Bet
{
    use Team, Competition, BetType, HashTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="float")
     * @Assert\GreaterThan(value = 0)
     */
    private $odds;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $home;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $visitor;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $competition;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $betType;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="integer")
     */
    private $pronostic;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    private $isBank;

    /**
     * @var Ticket
     *
     * @ORM\ManyToOne(targetEntity="Bettingup\TicketBundle\Entity\AbstractTicket", inversedBy="bets")
     */
    private $ticket;

    public function __construct()
    {
        $this->hash        = $this->generateHash();
        $this->odds        = 0.0;
        $this->status      = true;
        $this->isBank      = false;
        $this->home        = 0;
        $this->visitor     = 0;
        $this->competition = 0;
        $this->betType     = 0;
        $this->pronostic   = 0;
    }

    public function setOdds($odds)
    {
        $this->odds = $odds;

        return $this;
    }

    public function getOdds()
    {
        return $this->odds;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setIsBank($isBank)
    {
        $this->isBank = $isBank;

        return $this;
    }

    public function getIsBank()
    {
        return $this->isBank;
    }

    public function setTicket(AbstractTicket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getTicket()
    {
        return $this->ticket;
    }

    public function setHome($home)
    {
        if (!isset(self::getTeams()[$home])) {
            throw new OutOfBoundsException("Team doesn't exists.", 400);
        }
        //die(var_dump('ok'));
        $this->home = $home;

        return $this;
    }

    public function getHome()
    {
        return self::getTeams()[$this->home];
    }

    public function setVisitor($visitor)
    {
        if (!isset(self::getTeams()[$visitor])) {
            throw new OutOfBoundsException("Team doesn't exists.", 400);
        }

        $this->visitor = $visitor;

        return $this;
    }

    public function getVisitor()
    {
        return self::getTeams()[$this->visitor];
    }

    public function setCompetition($competition)
    {
        if (!isset(self::getCompetitions()[$competition])) {
            throw new OutOfBoundsException("Competition doesn't exists.", 400);
        }

        $this->competition = $competition;

        return $this;
    }

    public function getCompetition()
    {
        return self::getCompetitions()[$this->competition];
    }

    public function setBetType($betType)
    {
        if (!isset(self::getBetTypes()[$betType])) {
            throw new OutOfBoundsException("BetType doesn't exists.", 400);
        }

        $this->betType = $betType;

        return $this;
    }

    public function getBetType()
    {
        return self::getBetTypes()[$this->betType];
    }

    public function setPronostic($pronostic)
    {
        if (!isset(self::getBetTypesChoices()[$this->betType][$pronostic])) {
            throw new OutOfBoundsException("Pronostic doesn't exists.", 400);
        }

        $this->pronostic = $pronostic;

        return $this;
    }

    public function getPronostic()
    {
        return self::getBetTypesChoices()[$this->betType][$this->pronostic];
    }
}
