<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Directory is not readable" exception class
 *
 * Class DirNotReadable
 * @package Runn\Fs\Exceptions
 */
class DirNotReadable
    extends Exception
{

    /**
     * DirNotReadable constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['DIR_NOT_READABLE'], $previous);
    }

}