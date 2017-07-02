<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Dir already exists" exception class
 *
 * Class DirAlreadyExists
 * @package Runn\Fs\Exceptions
 */
class DirAlreadyExists
    extends Exception
{

    /**
     * DirAlreadyExists constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['DIR_ALREADY_EXISTS'], $previous);
    }

}