<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

class EmptyPath
    extends Exception
{

    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['EMPTY_PATH'], $previous);
    }

}