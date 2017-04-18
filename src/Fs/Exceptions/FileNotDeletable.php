<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File is not deletable" exception
 *
 * Class FileNotDeletable
 * @package Runn\Fs\Exceptions
 */
class FileNotDeletable
    extends Exception
{

    /**
     * FileNotDeletable constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NOT_DELETABLE'], $previous);
    }

}