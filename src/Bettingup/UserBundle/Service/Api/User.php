<?php

namespace Bettingup\UserBundle\Service\Api;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException;

use Bettingup\UserBundle\Exception\UserNotFoundException;

class User
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function get($slug)
    {
        try {
            return $this->em->getRepository('BettingupUserBundle:User')->get($slug);
        } catch (NoResultException $e) {
            throw new UserNotFoundException;
        }
    }
}
