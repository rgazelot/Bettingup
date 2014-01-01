<?php

namespace Bettingup\ApiBundle\Service;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Security\Core\SecurityContextInterface,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Bets\UserBundle\Entity\User,
    Bets\UserBundle\Exception\UserNotFoundException;

class Login
{
    private $em;
    private $security;

    public function __construct(EntityManager $em, SecurityContextInterface $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function authenticate($token)
    {
        $user = $this->em->getRepository('BetsUserBundle:User')->findOneby(['apiKey' => $token]);

        if (!$user instanceof User) {
            throw new UserNotFoundException;
        }

        $this->security->setToken(new UsernamePasswordToken($user, null, 'main', $user->getRoles()));
    }
}
