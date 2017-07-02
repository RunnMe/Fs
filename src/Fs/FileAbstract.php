<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\InvalidFileClass;

/**
 * "Abstract" file
 * Contains methods that are common for files, dirs and links
 * Includes factory method for files/dirs
 *
 * Class FileAbstract
 * @package Runn\Fs
 */
abstract class FileAbstract
    implements FileInterface
{

    use PathAwareTrait;

    /**
     * @param string $path
     * @param string|null $class
     * @return static
     * @throws \Runn\Fs\Exceptions\InvalidFileClass
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public static function instance($path, $class = null)
    {
        if (null !== $class && !is_subclass_of($class, self::class)) {
            throw new InvalidFileClass;
        }

        if (!file_exists($path)) {
            throw new FileNotExists;
        }

        if (is_file($path)) {
            $class = $class ?? File::class;
            return new $class($path);
        } elseif (is_dir($path)) {
            $class = $class ?? Dir::class;
            return new $class($path);
        }
    }

    /**
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        if (!empty($path)) {
            $this->setPath($path);
        }
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     */
    public function exists(): bool
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }
        return file_exists($this->getPath());
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isFile(): bool
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }
        if (!file_exists($this->getPath())) {
            throw new FileNotExists;
        }
        return is_file($this->getPath());
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isDir(): bool
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }
        if (!file_exists($this->getPath())) {
            throw new FileNotExists;
        }
        return is_dir($this->getPath());
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isLink(): bool
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }
        if (!file_exists($this->getPath())) {
            throw new FileNotExists;
        }
        return is_link($this->getPath());
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isReadable(): bool
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        return is_readable($this->getPath());
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isWritable(): bool
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        return is_writable($this->getPath());
    }

    /**
     * @param \DateTimeInterface|int|null $time
     * @return $this
     */
    abstract public function touch($time = null);

    /**
     * @return $this
     */
    abstract public function create();

    /**
     * @param bool $clearstatcache
     * @return int
     */
    abstract public function mtime($clearstatcache = true);

}