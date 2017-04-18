<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File already exists" exception class
 *
 * Class FileAlreadyExists
 * @package Runn\Fs\Exceptions
 */
class FileAlreadyExists
    extends Exception
{

    /**
     * FileAlreadyExists constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_ALREADY_EXISTS'], $previous);
    }

}