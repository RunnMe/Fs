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
        'FILE_NOT_EXISTS'        => 2,
        'FILE_ALREADY_EXISTS'    => 3,
        'FILE_NOT_READABLE'      => 4,
        'FILE_NOT_WRITABLE'      => 5,
        'FILE_NOT_DELETABLE'     => 6,
        'FILE_IS_DIR'            => 7,
        'FILE_NULL_CONTENTS'     => 8,
        'FILE_DESERIALIZE_ERROR' => 9,
        'DIR_IS_FILE'            => 10,
    ];

}