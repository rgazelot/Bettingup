<?php

namespace Bettingup\UserBundle\Service\Api;

use InvalidArgumentException;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException;

use Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\FormTypeInterface,

    Symfony\Component\HttpKernel\Exception\BadRequestHttpException,

    Symfony\Component\Security\Core\Encoder\EncoderFactory;

use Bettingup\UserBundle\Exception\UserNotFoundException,
    Bettingup\UserBundle\Entity\User as UserEntity,
    Bettingup\UserBundle\Form\Api\UserType,

    Bettingup\CoreBundle\Exception\FormNotValidException;

class User
{
    private $em;
    private $formFactory;
    private $encoder;

    public function __construct(EntityManager $em, FormFactory $formFactory, EncoderFactory $encoder)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->encoder = $encoder;
    }

    public function get($slug)
    {
        try {
            return $this->em->getRepository('BettingupUserBundle:User')->get($slug);
        } catch (NoResultException $e) {
            throw new UserNotFoundException;
        }
    }

    public function create(array $data)
    {
        $user = new UserEntity;

        if (!isset($data['password'])) {
            throw new InvalidArgumentException("password must be defined.");
        }

        $encodedPassword = $this->encoder->getEncoder($user)->encodePassword($data['password'], $data['password']);
        $user->setPassword($encodedPassword);

        $this->bindUser(new UserType, $user, $data);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function bindUser(FormTypeInterface $formType, UserEntity $user, array $data)
    {
        $form = $this->formFactory->create($formType, $user)->submit($data, false);

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }
    }
}
