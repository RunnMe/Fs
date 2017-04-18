<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File is dir" exception class
 *
 * Class FileIsDir
 * @package Runn\Fs\Exceptions
 */
class FileIsDir
    extends Exception
{

    /**
     * FileIsDir constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_IS_DIR'], $previous);
    }

}