<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Invalid file" exception class
 *
 * Class InvalidFile
 * @package Runn\Fs\Exceptions
 */
class InvalidFile
    extends Exception
{

    /**
     * InvalidFile constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['INVALID_FILE'], $previous);
    }

}