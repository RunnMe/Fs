<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Invalid directory" exception class
 *
 * Class InvalidDir
 * @package Runn\Fs\Exceptions
 */
class InvalidDir
    extends Exception
{

    /**
     * InvalidDir constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['INVALID_DIR'], $previous);
    }

}