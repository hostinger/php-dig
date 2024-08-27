<?php

namespace Hostinger\Dig\Exceptions;

use RuntimeException;

class DigExecException extends RuntimeException
{
    public function __construct(string $reason)
    {
        parent::__construct("Unable to run dig: $reason");
    }
}
