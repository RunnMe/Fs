<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Dir not exists" exception class
 *
 * Class DirNotExists
 * @package Runn\Fs\Exceptions
 */
class DirNotExists
    extends Exception
{

    /**
     * DirNotExists constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['DIR_NOT_EXISTS'], $previous);
    }

}