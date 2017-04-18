<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File is not readable" exception class
 *
 * Class FileNotReadable
 * @package Runn\Fs\Exceptions
 */
class FileNotReadable
    extends Exception
{

    /**
     * FileNotReadable constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NOT_READABLE'], $previous);
    }

}