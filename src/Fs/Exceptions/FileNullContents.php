<?php

namespace Runn\Fs\Exceptions;

use Runn\Fs\Exception;

/**
 * "File contents is null" exception class
 *
 * Class FileNullContents
 * @package Runn\Fs\Exceptions
 */
class FileNullContents
    extends Exception
{

    /**
     * FileNullContents constructor.
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, self::CODES['FILE_NULL_CONTENTS'], $previous);
    }

}