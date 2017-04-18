<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File not exists" exception class
 *
 * Class FileNotExists
 * @package Runn\Fs\Exceptions
 */
class FileNotExists
    extends Exception
{

    /**
     * FileNotExists constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NOT_EXISTS'], $previous);
    }

}