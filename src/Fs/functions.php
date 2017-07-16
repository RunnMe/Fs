<?php

namespace Runn\Fs;

/**
 * Is current OS Windows?
 * @return bool
 */
function isWindows()
{
    /** @todo @7.2 PHP_OS_FAMILY  != 'Windows' */
    return in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows']);
}