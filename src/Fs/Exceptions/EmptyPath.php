<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Empty path" exception class
 *
 * Class EmptyPath
 * @package Runn\Fs\Exceptions
 */
class EmptyPath
    extends Exception
{

    /**
     * EmptyPath constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['EMPTY_PATH'], $previous);
    }

}