<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\CopyError;

/**
 * Is current OS Windows?
 * @codeCoverageIgnore
 * @return bool
 */
function isWindows(): bool
{
    /** @7.2 PHP_OS_FAMILY  == 'Windows' */
    return in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows']);
}

/**
 * Is current OS MacOS?
 * @codeCoverageIgnore
 * @return bool
 */
function isMacos(): bool
{
    /** @7.2 PHP_OS_FAMILY  == 'Darwin' */
    return in_array(PHP_OS, ['Darwin']);
}

/**
 * Is current OS Linux?
 * @codeCoverageIgnore
 * @return bool
 */
function isLinux(): bool
{
    /** @7.2 PHP_OS_FAMILY  == 'BSD', 'Linux' */
    return false !== stripos(PHP_OS, 'bsd') || false !== stripos(PHP_OS, 'gnu')
        || false !== stripos(PHP_OS, 'linux') || 0 === stripos(PHP_OS, 'dragonfly');
}

/**
 * Can use "cp" console command?
 * @codeCoverageIgnore
 * @return bool
 */
function canCp(): bool
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
function canXcopy(): bool
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
 * @codeCoverageIgnore
 * Copies source to destination via "cp" command.
 * @param string $src Source
 * @param string $dst Destination
 * Acceptable copy operations:
 * - an existing source file to a destination file (overwriting allowed);
 * - an existing source file to a destination directory (may not exist);
 * - an existing source directory (contents) to a destination directory (may not exist).
 * @return int
 * @throws CopyError
 */
function cp(string $src, string $dst): int
{
    if (isMacos()) {
        $cmd = '\\cp -fpR "' . $src . '" "' . $dst . '" &>/dev/null';
    } else {
        if (is_file($src)) {
            $cmd = '\\cp -f --no-preserve=timestamps --strip-trailing-slashes "' . $src . '" "' . $dst . '" 2>&1 > /dev/null';
        } else {
            $cmd = '\\cp -fTR --no-preserve=timestamps --strip-trailing-slashes "' . $src . '" "' . $dst . '" 2>&1 > /dev/null';
        }
    }
    exec($cmd, $out, $code);
    if (0 !== $code) {
        throw new CopyError;
    }
    return $code;
}

/**
 * @codeCoverageIgnore
 * Copies source to destination via "xcopy" command.
 * @param string $src Source
 * @param string $dst Destination
 * Acceptable copy operations:
 * - an existing source file to a destination file (overwriting allowed);
 * - an existing source file to a destination directory (may not exist);
 * - an existing source directory (contents) to a destination directory (may not exist).
 * @return int
 * @throws CopyError
 */
function xcopy(string $src, string $dst): int
{
    if (is_file($src)) {
        $cmd = 'xcopy "' . $src . '" "' . $dst . '" /i /h /r /y 2>/NUL';
    } else {
        $cmd = 'xcopy "' . $src . '" "' . $dst . '" /i /s /e /h /r /y 2>/NUL';
    }

    if (is_dir($src)) {
        $cmd = 'echo D | ' . $cmd;
    } else {
        $cmd = 'echo F | ' . $cmd;
    }

    exec($cmd, $out, $code);
    //var_dump($cmd);var_dump($code);
    if (0 !== $code) {
        throw new CopyError;
    }
    return $code;
}

/**
 * Copies file or directory (recursive) via PHP "copy()" function.
 * @param string $src Source
 * @param string $dst Destination
 * Acceptable copy operations:
 * - an existing source file to a destination file (overwriting allowed);
 * - an existing source file to a destination directory (may not exist);
 * - an existing source directory (contents) to a destination directory (may not exist).
 * @return bool
 */
function copy(string $src, string $dst): bool
{
    if (is_file($src)) {
        return \copy($src, $dst);
    }
    @mkdir($dst);
    $iterator = new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) {
            if (!file_exists($dst . DIRECTORY_SEPARATOR . $files->getSubPathName())) {
                $res = @mkdir($dst . DIRECTORY_SEPARATOR . $files->getSubPathName());
            } else {
                $res = true;
            }
        } else {
            $res = \copy($file, $dst . DIRECTORY_SEPARATOR . $files->getSubPathName());
        }
        if (false === $res) {
            return $res;
        }
    }
    return true;
}
