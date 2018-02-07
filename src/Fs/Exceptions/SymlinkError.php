<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Symlink error" exception class
 *
 * Class SymlinkError
 * @package Runn\Fs\Exceptions
 */
class SymlinkError
    extends Exception
{

    /**
     * SymlinkError constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['SYMLINK_ERROR'], $previous);
    }

}