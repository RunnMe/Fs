<?php

namespace Runn\Fs\Exceptions;

/**
 * "Invalid directory" exception class
 *
 * Class InvalidDir
 * @package Runn\Fs\Exceptions
 */
class InvalidDir
    extends InvalidFile
{

    /**
     * InvalidDir constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
        $this->code = self::CODES['INVALID_DIR'];
    }

}