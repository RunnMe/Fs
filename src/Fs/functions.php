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

/**
 * Can use "cp" command?
 * @return bool
 */
function canCp()
{
    if (!isWindows()) {
        exec('\\cp --help 2>/dev/null', $out, $code);
        if (0 === $code) {
            return true;
        }
    }
    return false;
}

/**
 * Can use "xcopy" command? (windows-only)
 * @return bool
 */
function canXcopy()
{
    if (isWindows()) {
        exec('xcopy /? 2>NUL', $out, $code);
        if (0 === $code) {
            return true;
        }
    }
    return false;
}