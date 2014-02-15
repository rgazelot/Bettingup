<?php

namespace Bettingup\TicketBundle\Tests\Service\Api;

use PHPUnit_Framework_TestCase;

use Bettingup\CoreBundle\Traits\Tests\MockBuilder,
    Bettingup\CoreBundle\Traits\Tests\ReflectionUtils,

    Bettingup\TicketBundle\Service\Api\Ticket,
    Bettingup\TicketBundle\Entity\Bet,
    Bettingup\TicketBundle\Entity\Ticket as TicketEntity;


class TicketTest extends PHPUnit_Framework_TestCase
{
    use ReflectionUtils, MockBuilder;

    /**
     * @dataProvider invalidChoose
     * @expectedException InvalidArgumentException
     */
    public function testExtractCombinaisonsWithInvalidChoose($choose)
    {
        $ticket = new Ticket(
            $this->getMockWithoutConstructor('Doctrine\ORM\EntityManager'),
            $this->getMockWithoutConstructor('Symfony\Component\Form\FormFactory')
        );
        $this->getAccessibleMethod('Bettingup\TicketBundle\Service\Api\Ticket', 'extractCombinaisons')->invokeArgs($ticket, [[1.3, 1.2, 1.2], $choose]);
    }

    public function invalidChoose()
    {
        return [
            [3],
            [-1],
            [4],
        ];
    }

    /**
     * @dataProvider extractCombinaisonsData
     */
    public function testExtractCombinaisons(array $array, $choose, array $expected)
    {
        $ticket = new Ticket(
            $this->getMockWithoutConstructor('Doctrine\ORM\EntityManager'),
            $this->getMockWithoutConstructor('Symfony\Component\Form\FormFactory')
        );
        $combinaisons = $this->getAccessibleMethod('Bettingup\TicketBundle\Service\Api\Ticket', 'extractCombinaisons')->invokeArgs($ticket, [$array, $choose]);

        $this->assertEquals($expected, $combinaisons);
    }

    public function extractCombinaisonsData()
    {
        return [
            [[1.5, 1.2, 1.6], 2, [[1.5, 1.2], [1.5, 1.6], [1.2, 1.6]]],
            [[1.5, 1.2, 1.6, 1.2], 2, [[1.5, 1.2], [1.5, 1.6], [1.5, 1.2], [1.2, 1.6], [1.2, 1.2], [1.6, 1.2]]],
            [[1.5, 1.2, 1.6, 1.2], 3, [[1.5, 1.2, 1.6], [1.5, 1.2, 1.2], [1.5, 1.6, 1.2], [1.2, 1.6, 1.2]]],
            [[1.5, 1.2, 1.6, 1.2, 1.25], 2, [[1.5, 1.2], [1.5, 1.6], [1.5, 1.2], [1.5, 1.25], [1.2, 1.6], [1.2, 1.2], [1.2, 1.25], [1.6, 1.2], [1.6, 1.25], [1.2, 1.25]]],
            [[1.5, 1.2, 1.6, 1.2, 1.25], 3, [[1.5, 1.2, 1.6], [1.5, 1.2, 1.2], [1.5, 1.2, 1.25], [1.5, 1.6, 1.2], [1.5, 1.6, 1.25], [1.5, 1.2, 1.25], [1.2, 1.6, 1.2], [1.2, 1.6, 1.25], [1.2, 1.2, 1.25], [1.6, 1.2, 1.25]]],
        ];
    }

    /**
     * @dataProvider combineSystemData
     */
    public function testCombineSystemData(TicketEntity\System $ticketEntity, $betPerMatch, $systemChoose, array $expectedCombinaisons, $expectedProfit)
    {
        $ticket = new Ticket(
            $this->getMockWithoutConstructor('Doctrine\ORM\EntityManager'),
            $this->getMockWithoutConstructor('Symfony\Component\Form\FormFactory')
        );
        list($combinaisons, $profit) = $this->getAccessibleMethod('Bettingup\TicketBundle\Service\Api\Ticket', 'combineSystemData')->invokeArgs($ticket, [$ticketEntity, $betPerMatch, $systemChoose]);

        $this->assertEquals($expectedCombinaisons, $combinaisons);
        $this->assertEquals($expectedProfit, $profit);
    }

    public function combineSystemData()
    {
        $bets = [
            (new Bet)->setOdds(1.2),
            (new Bet)->setOdds(1.3),
            (new Bet)->setOdds(1.4),
        ];

        $ticket = $this->getMockWithoutConstructor('Bettingup\TicketBundle\Entity\Ticket\System');
        $ticket->expects(self::once())
            ->method('getBets')
            ->will(self::returnValue($bets));

        return [
            [$ticket, 10, 2, [['combine' => [1, 2], 'odds' => 1.56], ['combine' => [1, 3], 'odds' => 1.68], ['combine' => [2, 3], 'odds' => 1.82]], 50.6],
        ];
    }

    /**
     * @dataProvider combineSystemDataWithBank
     */
    public function testCombineSystemDataWithBank(TicketEntity\System $ticketEntity, $betPerMatch, $systemChoose, array $expectedCombinaisons, $expectedProfit)
    {
        $ticket = new Ticket(
            $this->getMockWithoutConstructor('Doctrine\ORM\EntityManager'),
            $this->getMockWithoutConstructor('Symfony\Component\Form\FormFactory')
        );
        list($combinaisons, $profit) = $this->getAccessibleMethod('Bettingup\TicketBundle\Service\Api\Ticket', 'combineSystemData')->invokeArgs($ticket, [$ticketEntity, $betPerMatch, $systemChoose]);

        $this->assertEquals($expectedCombinaisons, $combinaisons);
        $this->assertEquals($expectedProfit, $profit);
    }

    public function combineSystemDataWithBank()
    {
        $bets = [
            (new Bet)->setOdds(1.2),
            (new Bet)->setOdds(1.3),
            (new Bet)->setOdds(1.4),
            (new Bet)->setOdds(1.5)->setIsBank(true),
        ];

        $ticket = $this->getMockWithoutConstructor('Bettingup\TicketBundle\Entity\Ticket\System');
        $ticket->expects(self::once())
            ->method('getBets')
            ->will(self::returnValue($bets));

        return [
            [$ticket, 10, 2, [['combine' => [1, 2, 4], 'odds' => 2.34], ['combine' => [1, 3, 4], 'odds' => 2.52], ['combine' => [2, 3, 4], 'odds' => 2.73]], 75.9],
        ];
    }

    public function testComputeProfiteForSimpleAndCombine()
    {
        $bets = [
            (new Bet)->setOdds(1.2)->setStatus(true),
            (new Bet)->setOdds(1.3)->setStatus(true),
        ];

        $ticketEntity = $this->getMockWithoutConstructor('Bettingup\TicketBundle\Entity\Ticket\Simple');
        $ticketEntity->expects(self::any())
            ->method('getBets')
            ->will(self::returnValue($bets));
        $ticketEntity->expects(self::any())
            ->method('getAmount')
            ->will(self::returnValue(100));

        $ticket = new Ticket(
            $this->getMockWithoutConstructor('Doctrine\ORM\EntityManager'),
            $this->getMockWithoutConstructor('Symfony\Component\Form\FormFactory')
        );
        $profit = $this->getAccessibleMethod('Bettingup\TicketBundle\Service\Api\Ticket', 'computeProfiteForSimpleAndCombine')->invokeArgs($ticket, [$ticketEntity]);

        $this->assertEquals(56, $profit);
    }

    public function testComputeProfiteForSimpleAndCombineWithFalse()
    {
        $bets = [
            (new Bet)->setOdds(1.2)->setStatus(true),
            (new Bet)->setOdds(1.3)->setStatus(false),
        ];

        $ticketEntity = $this->getMockWithoutConstructor('Bettingup\TicketBundle\Entity\Ticket\Simple');
        $ticketEntity->expects(self::any())
            ->method('getBets')
            ->will(self::returnValue($bets));
        $ticketEntity->expects(self::any())
            ->method('getAmount')
            ->will(self::returnValue(100));

        $ticket = new Ticket(
            $this->getMockWithoutConstructor('Doctrine\ORM\EntityManager'),
            $this->getMockWithoutConstructor('Symfony\Component\Form\FormFactory')
        );
        $profit = $this->getAccessibleMethod('Bettingup\TicketBundle\Service\Api\Ticket', 'computeProfiteForSimpleAndCombine')->invokeArgs($ticket, [$ticketEntity]);

        $this->assertEquals(-100, $profit);
    }
}
