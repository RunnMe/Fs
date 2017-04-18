<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\Exceptions\FileNullContents;

/**
 * Trait FileAsStorageTrait
 * @package Runn\Fs
 *
 * @implements \Runn\Fs\FileAsStorageInterface
 *
 * @mixin \Runn\Fs\File
 */
trait FileAsStorageTrait
    /*implements FileAsStorageInterface*/
{

    protected $contents = null;

    protected function processContentsAfterLoad($contents)
    {
        return $contents;
    }

    /**
     * @return $this
     * @throws \Runn\Fs\Exceptions\FileNotReadable
     */
    public function load()
    {
        if ($this->isReadable()) {
            $this->contents = $this->processContentsAfterLoad(file_get_contents($this->getPath()));
            return $this;
        } else {
            throw new FileNotReadable;
        }
    }

    protected function processContentsBeforeSave($contents)
    {
        return $contents;
    }

    /**
     * @return $this
     * @throws \Runn\Fs\Exceptions\FileNotWritable
     * @throws \Runn\Fs\Exceptions\FileNullContents
     */
    public function save()
    {
        if (null === $this->contents) {
            throw new FileNullContents;
        }
        if (!$this->exists()) {
            $this->create();
        }
        if ($this->isWritable()) {
            file_put_contents($this->getPath(), $this->processContentsBeforeSave($this->contents));
            return $this;
        } else {
            throw new FileNotWritable;
        }
    }

    /**
     * @return string|null
     */
    public function get()
    {
        return $this->contents;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function set($value)
    {
        $this->contents = $value;
        return $this;
    }

}