<?php

namespace Hostinger\Dig\Exceptions;

use RuntimeException;

class UnsupportedRecordTypeException extends RuntimeException
{
    public function __construct(int $type)
    {
        parent::__construct("Unsupported record type provided: $type");
    }
}
