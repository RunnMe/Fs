<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\CopyError;

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
    /** @7.2 PHP_OS_FAMILY  != 'Darwin' */
    return in_array(PHP_OS, ['Darwin']);
}

/**
 * Is current OS Linux?
 * @codeCoverageIgnore
 * @return bool
 */
function isLinux()
{
    /** @7.2 PHP_OS_FAMILY  != 'BSD', 'Linux' */
    return false !== stripos(PHP_OS, 'bsd') || false !== stripos(PHP_OS, 'gnu')
        || false !== stripos(PHP_OS, 'linux') || 0 === stripos(PHP_OS, 'dragonfly');
}

/**
 * Can use "cp" console command?
 * @codeCoverageIgnore
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
 * Can use "xcopy" console command? (windows-only)
 * @codeCoverageIgnore
 * @return bool
 */
function canXcopy()
{
    if (isWindows()) {
        exec('xcopy /? >NUL', $out, $code);
        if (0 === $code) {
            return true;
        }
    }
    return false;
}

/**
 * @todo
 * @codeCoverageIgnore
 * Copies source file to destination via "cp" command
 * @param string $src
 * @param string $dst
 * @return int
 * @throws CopyError
 */
function cpFile($src, $dst)
{
    if (isMacos()) {
        $cmd = '\\cp -fp "' . $src . '" "' . $dst . '" &>/dev/null';
    } else {
        $cmd = '\\cp -f --no-preserve=timestamps --strip-trailing-slashes "' . $src . '" "' . $dst . '" &>/dev/null';
    }
    exec($cmd, $out, $code);
    if (0 !== $code) {
        throw new CopyError;
    }
    return $code;
}

/**
 * @codeCoverageIgnore
 * Copies source to destination via "xcopy" command
 * @param string $src
 * @param string $dst
 * @return int
 * @throws CopyError
 */
function xcopy($src, $dst)
{
    $cmd = 'xcopy "' . $src . '" "' . $dst . '" /i /s /e /h /r /y 2>/NUL';
    if (is_dir($src)) {
        $cmd = 'echo D | ' . $cmd;
    } else {
        $cmd = 'echo F | ' . $cmd;
    }
    exec($cmd, $out, $code);
    if (0 !== $code) {
        throw new CopyError;
    }
    return $code;
}

/**
 * Copies one file via PHP "copy()" function
 * @param string $src Source file name (full path)
 * @param string $dst Destination file name (full path)
 * @return bool
 */
function copyFile($src, $dst)
{
    return \copy($src, $dst);
}

/**
 * Copies directory (recursive) via PHP "copy()" function
 * @param string $src Source dir name (full path)
 * @param string $dst Destination dir name (full path)
 * @return bool
 */
function copyDir($src, $dst)
{
    $list = array_diff(scandir($src), ['.', '..']);
    foreach ($list as $file) {
        if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
            if (!file_exists($dst . DIRECTORY_SEPARATOR . $file)) {
                mkdir($dst . DIRECTORY_SEPARATOR . $file);
            }
            $res = copyDir($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
        } else {
            $res = copyFile($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
        }
        if (false === $res) {
            return false;
        }
    }
    return true;
}

/**
 * Copies file or directory (recursive) via PHP "copy()" function
 * @param string $src Source dir name (full path)
 * @param string $dst Destination dir name (full path)
 * @return bool
 */
function copy($src, $dst)
{
    if (is_dir($src)) {
        return copyDir($src, $dst);
    }
    return copyFile($src, $dst);
}