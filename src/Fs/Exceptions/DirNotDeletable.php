<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Dir is not deletable" exception
 *
 * Class DirNotDeletable
 * @package Runn\Fs\Exceptions
 */
class DirNotDeletable extends Exception
{

    /**
     * DirNotDeletable constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['DIR_NOT_DELETABLE'], $previous);
    }

}
