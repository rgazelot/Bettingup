<?php

namespace Bettingup\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;

use Bettingup\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $factory = $this->container->get('security.encoder_factory');

        $user = (new User)
            ->setUsername('admin')
            ->setPassword('pass')
            ->encodePassword($factory)
            ->setEmail('admin@bettingup.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setIsActive(true);

        $manager->persist($user);
        $manager->flush();
    }
}
