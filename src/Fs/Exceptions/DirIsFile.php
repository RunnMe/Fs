<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Dir is file" exception class
 *
 * Class DirIsFile
 * @package Runn\Fs\Exceptions
 */
class DirIsFile
    extends Exception
{

    /**
     * DirIsFile constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['DIR_IS_FILE'], $previous);
    }

}