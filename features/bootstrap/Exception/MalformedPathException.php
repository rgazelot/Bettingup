<?php

namespace features\bootstrap\Exception;

use Exception,
    InvalidArgumentException;

class MalformedPathException extends InvalidArgumentException
{
    public function __construct($char, $position, $str = 'Unexpected char "%1$s" at position "%1$d".', Exception $previous = null)
    {
        parent::__construct(sprintf('Malformed path : ' . $str, $char, $position), 0, $previous);
    }
}

