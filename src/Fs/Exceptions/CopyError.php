<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Copy error" exception class
 *
 * Class CopyError
 * @package Runn\Fs\Exceptions
 */
class CopyError
    extends Exception
{

    /**
     * CopyError constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['COPY_ERROR'], $previous);
    }

}