<?php

namespace Bettingup\ApiBundle\Tests\Exception;

use Bettingup\ApiBundle\Exception\ApiTokenException;

class ApiTokenExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $exception = new ApiTokenException;
        $this->assertEquals("Wrong Token.", $exception->getMessage());
        $this->assertEquals(400, $exception->getStatusCode());
    }
}
