<?php

namespace Runn\Fs\Exceptions;

/**
 * "Dir already exists" exception class
 *
 * Class DirAlreadyExists
 * @package Runn\Fs\Exceptions
 */
class DirAlreadyExists
    extends FileAlreadyExists
{

    /**
     * DirAlreadyExists constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->code = self::CODES['DIR_ALREADY_EXISTS'];
    }

}