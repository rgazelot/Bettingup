<?php

namespace Bettingup\UserBundle\Tests\Exception;

use Bettingup\UserBundle\Exception\UserNotFoundException;

class UserNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $exception = new UserNotFoundException;
        $this->assertEquals("User not found.", $exception->getMessage());
        $this->assertEquals(404, $exception->getStatusCode());
    }
}
