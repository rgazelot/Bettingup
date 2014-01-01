<?php

namespace Bettingup\ApiBundle\Tests\Exception;

use Bettingup\ApiBundle\Exception\ApiTokenNotFoundException;

class ApiTokenNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanciate()
    {
        $exception = new ApiTokenNotFoundException;
        $this->assertEquals("An User ApiKey must be passed in query args.", $exception->getMessage());
        $this->assertEquals(400, $exception->getStatusCode());
    }
}
