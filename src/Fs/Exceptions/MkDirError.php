<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Make directory error" exception class
 *
 * Class MkDirError
 * @package Runn\Fs\Exceptions
 */
class MkDirError
    extends Exception
{

    /**
     * MkDirError constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['MKDIR_ERROR'], $previous);
    }

}