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
        'INVALID_FILE_CLASS'     => 1,
        'EMPTY_PATH'             => 2,

        'INVALID_FILE'           => 101,
        'FILE_NOT_EXISTS'        => 102,
        'FILE_ALREADY_EXISTS'    => 103,
        'FILE_NOT_READABLE'      => 104,
        'FILE_NOT_WRITABLE'      => 105,
        'FILE_NOT_DELETABLE'     => 106,
        'FILE_NULL_CONTENTS'     => 107,
        'FILE_DESERIALIZE_ERROR' => 108,

        'INVALID_DIR'            => 201,
        'MKDIR_ERROR'            => 202,
        'DIR_ALREADY_EXISTS'     => 203,
        'DIR_NOT_EXISTS'         => 204,
        'DIR_TOUCH_ERROR'        => 205,
        'DIR_NOT_READABLE'       => 206,
    ];

}