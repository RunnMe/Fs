<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\DirAlreadyExists;
use Runn\Fs\Exceptions\DirNotDeletable;
use Runn\Fs\Exceptions\DirNotExists;
use Runn\Fs\Exceptions\DirNotReadable;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\InvalidDir;
use Runn\Fs\Exceptions\MkDirError;

class Dir extends FileAbstract
{

    /**
     * @param string $path
     * @param string $prefix
     * @return $this
     * @throws \Runn\Fs\Exceptions\InvalidDir
     */
    public function setPath(string $path, string $prefix = '')
    {
        $path = $prefix . $path;
        if (file_exists($path) && !is_dir($path)) {
            throw new InvalidDir;
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return false;
    }

    /**
     * Creates directory if one does not exist
     *
     * @param int $createMode
     * @return $this
     * @throws EmptyPath
     * @throws DirAlreadyExists
     * @throws MkDirError
     */
    public function create(int $createMode = 0755)
    {
        if ($this->exists()) {
            throw new DirAlreadyExists();
        }
        $res = @mkdir($this->getPath(), $createMode, true);
        if (false === $res) {
            throw new MkDirError;
        }
        @chmod($this->getPath(), $createMode);
        return $this;
    }

    /**
     * @param int $createMode
     * @return $this
     * @throws DirAlreadyExists
     * @throws Exceptions\EmptyPath
     * @throws MkDirError
     */
    public function make(int $createMode = 0755)
    {
        if ($this->exists()) {
            return $this;
        }
        $this->create($createMode);
        return $this;
    }

    /**
     * Deletes directory - recursively
     * @return $this
     * @throws DirNotDeletable
     *
     * @todo: delete by PHP, without shell commands
     */
    public function delete()
    {
        if (canRm()) {
            $res = rm($this->getRealPath());
            if (false === $res) {
                throw new DirNotDeletable();
            }
            return $this;
        }
        if (canRd()) {
            $res = rd($this->getRealPath());
            if (false === $res) {
                throw new DirNotDeletable();
            }
            return $this;
        }
    }

    /**
     * @param boolean $recursive
     * @return FileCollection
     * @throws DirNotReadable
     */
    public function list(bool $recursive = false)
    {
        if (!$this->isReadable()) {
            throw new DirNotReadable;
        }
        $path = realpath($this->getPath());

        $list = array_values(array_map(
            function ($f) use ($path) {
                return $path . DIRECTORY_SEPARATOR . $f;
            },
            array_diff(scandir($path), ['.', '..'])
        ));

        $ret = new FileCollection();
        foreach ($list as $file) {
            $ret[] = FileAbstract::instance($file);
            if ($recursive && is_dir($file)) {
                $ret->merge((new static($file))->list($recursive));
            }
        }

        return $ret;
    }

    /**
     * @param bool $clearstatcache
     * @param bool $only Only own directory's mtime, no-recursive
     * @return int
     * @throws EmptyPath
     * @throws \Runn\Fs\Exceptions\DirNotExists
     * @throws \Runn\Fs\Exceptions\DirNotReadable
     */
    public function mtime($clearstatcache = true, $only = false)
    {
        if (!$this->exists()) {
            throw new DirNotExists;
        }

        if (!$this->isReadable()) {
            throw new DirNotReadable;
        }

        if ($clearstatcache) {
            clearstatcache(true, $this->getPath());
        }

        $self = @filemtime($this->getPath() . DIRECTORY_SEPARATOR . '.');

        if ( $only || ($list = $this->list(true))->empty() ) {
            return $self;
        } else {
            return $list->reduce($self, function ($acc, FileAbstract $el) use ($clearstatcache) {
                $mtime = $el->mtime($clearstatcache);
                return $mtime > $acc ? $mtime : $acc;
            });
        }
    }

}