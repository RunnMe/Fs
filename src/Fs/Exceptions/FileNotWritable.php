<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File is not writable" exception class
 *
 * Class FileNotWritable
 * @package Runn\Fs\Exceptions
 */
class FileNotWritable
    extends Exception
{

    /**
     * FileNotWritable constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NOT_WRITABLE'], $previous);
    }

}