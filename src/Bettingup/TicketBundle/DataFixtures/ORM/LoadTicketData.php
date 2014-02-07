<?php

namespace Bettingup\TicketBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Bettingup\TicketBundle\Entity\Ticket;

class LoadTicketData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $simple = (new Ticket\Simple);
        $manager->persist($simple);

        $combine = (new Ticket\Combine);
        $manager->persist($combine);

        $system = (new Ticket\System(4));
        $manager->persist($system);

        $manager->flush();
    }
}
