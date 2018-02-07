<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "Directory touch error" exception class
 *
 * Class DirTouchError
 * @package Runn\Fs\Exceptions
 */
class DirTouchError
    extends Exception
{

    /**
     * DirTouchError constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['DIR_TOUCH_ERROR'], $previous);
    }

}