<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileNotDeletable;
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
class File extends FileAbstract implements FileAsStorageInterface
{

    use FileAsStorageTrait;

    /**
     * @param string $path
     * @param string $prefix
     * @return $this
     * @throws \Runn\Fs\Exceptions\InvalidFile
     */
    public function setPath(string $path, string $prefix = '')
    {
        $path = $prefix . $path;
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
     * @return $this
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileAlreadyExists
     * @throws \Runn\Fs\Exceptions\FileNotWritable
     */
    public function create()
    {
        if ($this->exists()) {
            throw new FileAlreadyExists;
        }
        $res = @touch($this->getPath());
        if (false === $res) {
            throw new FileNotWritable;
        }
        return $this;
    }

    /**
     * Deletes directory - recursively
     * @return $this
     * @throws FileNotDeletable
     *
     * @todo: delete by PHP, without shell commands
     */
    public function delete()
    {
        if (canRm()) {
            $res = rm($this->getRealPath(), false);
            if (false === $res) {
                throw new FileNotDeletable();
            }
            return $this;
        }
        if (canRd()) {
            $res = rd($this->getRealPath(), false);
            if (false === $res) {
                throw new FileNotDeletable();
            }
            return $this;
        }
    }


    /**
     * @param bool $clearstatcache
     * @return int
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
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
        return $time;
    }

    // passthru

}