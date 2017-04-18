<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

class FileNullContents
    extends Exception
{

    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NULL_CONTENTS'], $previous);
    }

}