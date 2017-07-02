<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;

/**
 * File mapper class
 * Represents one file (or dir or link)
 *
 * Class File
 * @package Runn\Fs
 */
class File
    implements FileInterface, FileAsStorageInterface
{

    use PathAwareTrait;
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
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotWritable
     */
    public function touch($time = null)
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->getTimestamp();
        }

        if (null === $time) {
            $res = @touch($this->getPath());
        } else {
            $res = @touch($this->getPath(), $time);
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
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     * @throws \Runn\Fs\Exceptions\FileNotReadable
     */
    public function mtime($clearstatcache = true)
    {
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        if ($clearstatcache) {
            clearstatcache(true, $this->getPath());
        }
        $time = @filemtime($this->getPath());
        if (false === $time) {
            throw new FileNotReadable;
        }
        return $time;
    }

    // passthru

}