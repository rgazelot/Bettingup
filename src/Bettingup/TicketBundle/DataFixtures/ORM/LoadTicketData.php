<?php

namespace Bettingup\TicketBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\Persistence\ObjectManager,
    Doctrine\Common\DataFixtures\AbstractFixture;

use Bettingup\TicketBundle\Entity\Ticket,
    Bettingup\TicketBundle\Entity\Bet;

class LoadTicketData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new Ticket\Simple)
            ->setHash('simple1')
            ->setAmount(10)
            ->setUser($this->getReference('user.behat.1'))
            ->addBet((new Bet)
                ->setHash('bet0001')
                ->setHome(1)
                ->setVisitor(2)
                ->setCompetition(0)
                ->setOdds(1.2)
                ->setStatus(true)
                ->setBetType(0)
                ->setPronostic(0)
            )
        );

        $manager->persist((new Ticket\Combine)
            ->setHash('combin1')
            ->setAmount(20)
            ->setUser($this->getReference('user.behat.2'))
            ->addBet((new Bet)
                ->setHash('bet0002')
                ->setHome(1)
                ->setVisitor(2)
                ->setCompetition(0)
                ->setOdds(1.2)
                ->setStatus(false)
                ->setBetType(0)
                ->setPronostic(0)
            )
            ->addBet((new Bet)
                ->setHash('bet0003')
                ->setHome(3)
                ->setVisitor(4)
                ->setCompetition(0)
                ->setOdds(1.5)
                ->setStatus(true)
                ->setBetType(1)
                ->setPronostic(0)
            )
        );

        $manager->flush();
    }

    public function getOrder()
    {
        return 50;
    }
}
