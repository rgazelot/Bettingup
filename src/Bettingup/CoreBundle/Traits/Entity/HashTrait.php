<?php

namespace Bettingup\CoreBundle\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HashTrait
{
    /**
     * @ORM\Column(type="string", length=7, nullable=false)
     *
     * @Symfony\Component\Validator\Constraints\NotNull
     * @Symfony\Component\Validator\Constraints\Regex(pattern="/^[a-zA-Z][a-zA-Z0-9]{6}$/", message="A valid hash must be exactly 7 alphanum characters, starting by a letter")
     */
    private $hash;

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    public static function generateHash()
    {
        return array_rand(array_flip(array_merge(range('a', 'z'), range('A', 'Z')))) . substr(sha1(uniqid(mt_rand(), true)), 0, 6);
    }
}
