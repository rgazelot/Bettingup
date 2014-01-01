<?php

namespace Bettingup\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiTokenException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct("Wrong Token.");
    }
}
