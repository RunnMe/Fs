<?php

namespace Runn\Fs;

/**
 * Filesystem exception
 *
 * Class Exception
 * @package Runn\Fs
 *
 * @codeCoverageIgnore
 */
abstract class Exception
    extends \Runn\Core\Exception
{

    const CODES = [
        'EMPTY_PATH'             => 1,
        'INVALID_FILE'           => 2,
        'FILE_NOT_EXISTS'        => 3,
        'FILE_ALREADY_EXISTS'    => 4,
        'FILE_NOT_READABLE'      => 5,
        'FILE_NOT_WRITABLE'      => 6,
        'FILE_NOT_DELETABLE'     => 7,
        'FILE_NULL_CONTENTS'     => 8,
        'FILE_DESERIALIZE_ERROR' => 9,
        'INVALID_DIR'            => 10,
        'MKDIR_ERROR'            => 11,
        'DIR_ALREADY_EXISTS'     => 12,
        'DIR_TOUCH_ERROR'        => 13,
        'DIR_NOT_READABLE'       => 14,
    ];

}