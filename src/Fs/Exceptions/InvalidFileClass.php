<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Invalid file class" exception class
 *
 * Class InvalidFileClass
 * @package Runn\Fs\Exceptions
 */
class InvalidFileClass
    extends Exception
{

    /**
     * InvalidFileClass constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['INVALID_FILE_CLASS'], $previous);
    }

}