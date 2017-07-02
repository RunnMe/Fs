<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\DirAlreadyExists;
use Runn\Fs\Exceptions\DirNotReadable;
use Runn\Fs\Exceptions\DirTouchError;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\InvalidDir;
use Runn\Fs\Exceptions\MkDirError;

class Dir
    extends FileAbstract
{

    /**
     * @param string $path
     * @return $this
     * @throws \Runn\Fs\Exceptions\InvalidDir
     */
    public function setPath(string $path)
    {
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
     * @param \DateTimeInterface|int|null $time
     * @return $this
     * @param int $createMode
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\MkDirError
     * @throws \Runn\Fs\Exceptions\DirTouchError
     */
    public function touch($time = null, $createMode = 0777)
    {
        if (empty($this->getPath())) {
            throw new EmptyPath();
        }

        $created = false;
        if (!$this->exists()) {
            $res = @mkdir($this->getPath(), $createMode, true);
            if (false === $res) {
                throw new MkDirError;
            }
            $created = true;
        }

        if ($time instanceof \DateTimeInterface) {
            $time = $time->getTimestamp();
        }

        $res = true;
        if (null === $time && !$created) {
            $res = @touch($this->getPath());
        } else {
            $res = @touch($this->getPath(), $time);
        }

        if (false === $res) {
            throw new DirTouchError;
        }

        return $this;
    }

    /**
     * @param int $createMode
     * @return $this
     */
    public function make($createMode = 0777)
    {
        if ($this->exists()) {
            return $this;
        }
        return $this->touch(null, $createMode);
    }

    /**
     * @param int $createMode
     * @return $this
     * @throws \Runn\Fs\Exceptions\DirAlreadyExists
     */
    public function create($createMode = 0777)
    {
        if ($this->exists()) {
            throw new DirAlreadyExists();
        }
        $this->make($createMode);
        return $this;
    }

    /**
     * @param int $order
     * @return \Runn\Fs\FileCollection
     * @throws \Runn\Fs\Exceptions\DirNotReadable
     */
    public function list($order = \SCANDIR_SORT_NONE)
    {
        if (!$this->isReadable()) {
            throw new DirNotReadable;
        }
        $path = $this->getPath();
        return new FileCollection(
            array_values(array_map(
                function ($f) use ($path) {
                    return FileAbstract::instance($path . DIRECTORY_SEPARATOR . $f);
                },
                array_diff(scandir($path, $order), ['.', '..'])
            ))
        );
    }

    /**
     * @param bool $clearstatcache
     * @return int
     */
    public function mtime($clearstatcache = true)
    {
        $list = $this->list();

        if ($list->empty()) {
            if ($clearstatcache) {
                clearstatcache(true, $this->getPath());
            }
            return @filemtime($this->getPath() . DIRECTORY_SEPARATOR . '.');
        } else {
            return $list->reduce(0, function ($acc, FileAbstract $el) use ($clearstatcache) {
                $mtime = $el->mtime($clearstatcache);
                return $mtime > $acc ? $mtime : $acc;
            });
        }
    }

}