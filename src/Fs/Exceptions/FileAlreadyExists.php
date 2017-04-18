<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

class FileAlreadyExists
    extends Exception
{

    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_ALREADY_EXISTS'], $previous);
    }

}