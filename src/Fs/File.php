<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;

/**
 * File mapper
 *
 * Class File
 * @package Runn\Fs
 */
class File
    implements FileInterface, FileAsStorageInterface
{

    use FileAsStorageTrait;

    /** @var string|null $path */
    protected $path = null;

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
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     */
    public function exists(): bool
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }
        return file_exists($this->path);
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isFile(): bool
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }
        if (!file_exists($this->path)) {
            throw new FileNotExists;
        }
        return is_file($this->path);
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isDir(): bool
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }
        if (!file_exists($this->path)) {
            throw new FileNotExists;
        }
        return is_dir($this->path);
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isLink(): bool
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }
        if (!file_exists($this->path)) {
            throw new FileNotExists;
        }
        return is_link($this->path);
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isReadable(): bool
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        return is_readable($this->path);
    }

    /**
     * @return bool
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     */
    public function isWritable(): bool
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        return is_writable($this->path);
    }

    /**
     * @param \DateTimeInterface|int|null $time
     * @return $this
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotWritable
     */
    public function touch($time = null)
    {
        if (empty($this->path)) {
            throw new EmptyPath;
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->getTimestamp();
        }

        if (null === $time) {
            $res = @touch($this->path);
        } else {
            $res = @touch($this->path, $time);
        }

        if (false === $res) {
            throw new FileNotWritable;
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \Runn\Fs\Exceptions\FileAlreadyExists
     */
    public function create()
    {
        if ($this->exists()) {
            throw new FileAlreadyExists;
        }
        $this->touch();
        return $this;
    }

    /**
     * @param bool $clearstatcache
     * @return int
     * @throws FileNotReadable
     */
    public function mtime($clearstatcache = true)
    {
        if (!$this->isReadable()) {
            throw new FileNotReadable;
        }
        if ($clearstatcache) {
            clearstatcache();
        }
        return filemtime($this->getPath());
    }

    // passthru

}