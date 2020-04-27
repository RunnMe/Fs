<?php

namespace Runn\Fs;

/**
 * "IS" functions
 */

/**
 * Is current OS Windows?
 * @codeCoverageIgnore
 * @return bool
 */
function isWindows():bool
{
    return PHP_OS_FAMILY === 'Windows';
}

/**
 * Is current OS MacOS?
 * @codeCoverageIgnore
 * @return bool
 */
function isMacos():bool
{
    return PHP_OS_FAMILY === 'Darwin';
}

/**
 * Is current OS Linux?
 * @codeCoverageIgnore
 * @return bool
 */
function isLinux():bool
{
    return !isWindows() && !isMacos();
}

/**
 * "CAN" functions
 */

/**
 * Can use *nix "rm" console command?
 * @codeCoverageIgnore
 * @return bool
 */
function canRm():bool
{
    if (!isWindows()) {
        exec('\\type \\rm &>/dev/null', $out, $code);
        if (0 === $code) {
            return true;
        }
    }
    return false;
}

/**
 * Can use Windows "rd" console command?
 * @codeCoverageIgnore
 * @return bool
 */
function canRd()
{
    if (isWindows()) {
        exec('rd /? >NUL', $out, $code);
        if (0 === $code) {
            return true;
        }
    }
    return false;
}

/**
 * Can use "cp" console command?
 * @codeCoverageIgnore
 * @return bool
 */
function canCp():bool
{
    if (!isWindows()) {
        exec('\\type \\cp &>/dev/null', $out, $code);
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
function canXcopy():bool
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
 * Low-level functions
 */

/**
 * Removes files or directories by *nix 'rm' command
 * Returns true on success, false on failure
 * @param $path
 * @param bool $recursively
 * @return bool
 */
function rm($path, $recursively = true):bool
{
    $cmd = sprintf('\\rm -' . ($recursively ? 'r' : '') . 'f  "%s" &>/dev/null', escapeshellarg($path));
    exec($cmd, $out, $code);
    return 0 == $code;
}

/**
 * Removes files or directories by Windows 'rd' command
 * Returns true on success, false on failure
 * @param $path
 * @param bool $recursively
 * @return bool
 */
function rd($path, $recursively = true):bool
{
    $cmd = sprintf('rd ' . ($recursively ? '/s' : '') . ' /q "%s" >NUL', escapeshellarg($path));
    exec($cmd, $out, $code);
    return 0 == $code;
}

/**
 * @todo
 * @codeCoverageIgnore
 * Copies source file to destination via "cp" command
 * @param string $src
 * @param string $dst
 * @return int
 */
function cpFile($src, $dst):bool
{
    if (isMacos()) {

    } else {
        $cmd = '\\cp -f --no-preserve=timestamps --strip-trailing-slashes "' . $src . '" "' . $dst . '" &>/dev/null';
    }
    exec($cmd, $out, $code);
    return 0 == $code;
}

/**
 * @todo
 * @codeCoverageIgnore
 * Copies source to destination via "xcopy" command
 * @param string $src
 * @param string $dst
 * @return int
 */
function xcopy($src, $dst):bool
{
    $cmd = 'xcopy "' . $src. '" "' . $dst . '" /i /s /e /h /r /y 2>/NUL';
    if (is_dir($src)) {
        $cmd = 'echo D | ' . $cmd;
    } else {
        $cmd = 'echo F | ' . $cmd;
    }
    exec($cmd, $out, $code);
    return 0 == $code;
}

/**
 * Copies one file via PHP "copy()" function
 * @param string $src Source file name (full path)
 * @param string $dst Destination file name (full path)
 * @return bool
 */
function copyFile($src, $dst):bool
{
    return \copy($src, $dst);
}

/**
 * Copies directory (recursive) via PHP "copy()" function
 * @param string $src Source dir name (full path)
 * @param string $dst Destination dir name (full path)
 * @return bool
 */
function copyDir($src, $dst):bool
{
    $list = array_diff(scandir($src), ['.', '..']);
    foreach ($list as $file) {
        if (is_dir($file)) {
            mkdir($dst . DIRECTORY_SEPARATOR . $file);
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
function copy($src, $dst):bool
{
    if (is_dir($src)) {
        return copyDir($src, $dst);
    } else {
        return copyFile($src, $dst);
    }
}
