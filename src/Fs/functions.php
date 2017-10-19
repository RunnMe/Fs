<?php

namespace Runn\Fs;

/**
 * Is current OS Windows?
 * @codeCoverageIgnore
 * @return bool
 */
function isWindows()
{
    /** @7.2 PHP_OS_FAMILY  != 'Windows' */
    return in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows']);
}

/**
 * Is current OS MacOS?
 * @codeCoverageIgnore
 * @return bool
 */
function isMacos()
{
    return in_array(PHP_OS, ['Darwin']);
}

/**
 * Can use "cp" console command?
 * @return bool
 */
function canCp()
{
    if (!isWindows()) {
        exec('\\type cp &>/dev/null', $out, $code);
        if (0 === $code) {
            return true;
        }
    }
    return false;
}

/**
 * Can use "xcopy" command? (windows-only)
 * @codeCoverageIgnore
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

/**
 * Copies source to destination via "xcopy" command
 * @param string $src
 * @param string $dst
 * @return int
 */
function xcopy($src, $dst)
{
    $cmd = 'xcopy "' . $src. '" "' . $dst . '" /i /s /e /h /r /y 2>/NUL';
    if (is_dir($src)) {
        $cmd = 'echo D | ' . $cmd;
    } else {
        $cmd = 'echo F | ' . $cmd;
    }
    exec($cmd, $out, $code);
    return $code;
}