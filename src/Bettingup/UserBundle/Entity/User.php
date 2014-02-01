<?php

namespace Bettingup\UserBundle\Entity;

use \Datetime;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bettingup\UserBundle\Entity\UserRepository")
 * @DoctrineAssert\UniqueEntity("username")
 * @DoctrineAssert\UniqueEntity("email")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="apiKey", type="string", unique=true)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $apiKey;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     */
    private $isActive;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     *
     * @Assert\NotNull()
     * @Assert\Type(type="array")
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="signupAt", type="datetime")
     *
     * @Assert\NotNull()
     * @Assert\DateTime()
     */
    private $signupAt;

    public function __construct()
    {
        $this->isActive = false;
        $this->signupAt = new Datetime;
        $this->roles    = ['ROLE_USER'];
        $this->apiKey   = sha1(uniqid(true) . time());
    }

    public function toArray($short = true)
    {
        $core = [
            'id'        => $this->getId(),
            'username'  => $this->getUsername(),
            'email'     => $this->getEmail(),
            'api_key'   => $this->getApiKey(),
            'is_active' => $this->getIsActive(),
            'roles'     => $this->getRoles(),
            'signup_at' => $this->getSignupAt(),
        ];

        if (false === $short) {
            $core = array_merge($core, [

            ]);
        }

        return $core;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function encodePassword($factory)
    {
        $pass = $factory->getEncoder($this)->encodePassword($this->password, $this->getSalt());

        $this->setPassword($pass);

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set signupAt
     *
     * @param \DateTime $signupAt
     * @return User
     */
    public function setSignupAt($signupAt)
    {
        $this->signupAt = $signupAt;

        return $this;
    }

    /**
     * Get signupAt
     *
     * @return \DateTime
     */
    public function getSignupAt()
    {
        return $this->signupAt;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            $this->id,
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }
}
