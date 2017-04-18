<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

class FileNotReadable
    extends Exception
{

    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NOT_READABLE'], $previous);
    }

}