<?php

namespace Runn\Fs\Exceptions;

/**
 * "Dir not exists" exception class
 *
 * Class DirNotExists
 * @package Runn\Fs\Exceptions
 */
class DirNotExists
    extends FileNotExists
{

    /**
     * DirNotExists constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->code = self::CODES['DIR_NOT_EXISTS'];
    }

}