<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\Exceptions\InvalidFile;

/**
 * File mapper class
 * Represents one file
 *
 * Class File
 * @package Runn\Fs
 */
class File
    extends FileAbstract
    implements FileAsStorageInterface
{

    use FileAsStorageTrait;

    /**
     * @param string $path
     * @return $this
     * @throws \Runn\Fs\Exceptions\InvalidFile
     */
    public function setPath(string $path)
    {
        if (file_exists($path) && !is_file($path)) {
            throw new InvalidFile;
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDir(): bool
    {
        return false;
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