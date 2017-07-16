<?php

namespace Runn\Fs\Exceptions;

/**
 * "Directory is not readable" exception class
 *
 * Class DirNotReadable
 * @package Runn\Fs\Exceptions
 */
class DirNotReadable
    extends FileNotReadable
{

    /**
     * DirNotReadable constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->code = self::CODES['DIR_NOT_READABLE'];
    }

}