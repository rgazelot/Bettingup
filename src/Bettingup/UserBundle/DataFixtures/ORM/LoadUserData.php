<?php

namespace Bettingup\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\Persistence\ObjectManager,
    Doctrine\Common\DataFixtures\AbstractFixture;

use Bettingup\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
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
            ->setHash('admin001')
            ->setPassword('pass')
            ->encodePassword($factory)
            ->setEmail('admin@bettingup.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setApiKey(sha1('admin'))
            ->setIsActive(true);

        $manager->persist($user);

        $this->loadBehatFixtures($manager);

        $manager->flush();
    }

    private function loadBehatFixtures(ObjectManager $manager)
    {
        $factory = $this->container->get('security.encoder_factory');

        $user = (new User)
            ->setUsername('behat')
            ->setHash('behat01')
            ->setPassword('pass')
            ->encodePassword($factory)
            ->setEmail('behat@bettingup.com')
            ->setRoles(['ROLE_USER'])
            ->setApiKey(sha1('behat'))
            ->setIsActive(true);

        $this->setReference('user.behat.1', $user);
        $manager->persist($user);

        $user = (new User)
            ->setUsername('behat2')
            ->setHash('behat02')
            ->setPassword('pass')
            ->encodePassword($factory)
            ->setEmail('behat2@bettingup.com')
            ->setRoles(['ROLE_USER'])
            ->setApiKey(sha1('behat2'))
            ->setIsActive(true);

        $this->setReference('user.behat.2', $user);
        $manager->persist($user);
    }

    public function getOrder()
    {
        return 1;
    }
}
