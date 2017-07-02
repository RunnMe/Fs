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
        'FILE_IS_DIR'            => 8,
        'FILE_NULL_CONTENTS'     => 9,
        'FILE_DESERIALIZE_ERROR' => 10,
        'DIR_IS_FILE'            => 11,
    ];

}