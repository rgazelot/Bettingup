<?php

namespace Bettingup\TicketBundle\Tests\Entity;

use PHPUnit_Framework_TestCase;

use Bettingup\TicketBundle\Entity\Bet;

class BetType extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetOutOfBoundsHome()
    {
        (new Bet)->setHome(1000000000000);
    }

    public function testSetAndGetHome()
    {
        $bet = (new Bet)->setHome(10);

        $this->assertEquals("Montpellier", $bet->getHome());
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetOutOfBoundsVisitor()
    {
        (new Bet)->setVisitor(1000000000000);
    }

    public function testSetAndGetVisitor()
    {
        $bet = (new Bet)->setVisitor(10);

        $this->assertEquals("Montpellier", $bet->getVisitor());
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetOutOfBoundsCompetition()
    {
        (new Bet)->setCompetition(1000000000000);
    }

    public function testSetAndGetCompetition()
    {
        $bet = (new Bet)->setCompetition(10);

        $this->assertEquals("Liga BBVA", $bet->getCompetition());
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetBetTypeWithoutOutOfBoundsBetType()
    {
        (new Bet)->setBetType(1000000000000, 0);
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSetBetTypeWithoutOutOfBoundsPronostic()
    {
        (new Bet)->setBetType(0, 1000000000000);
    }

    public function testSetBetType()
    {
        $bet = (new Bet)->setBetType(10, 2);

        $this->assertEquals("Nombre de buts marqués par l'équipe 1 au cours de la 2ème mi-temps:", $bet->getBetType());
        $this->assertEquals("2 buts", $bet->getPronostic());
    }
}
