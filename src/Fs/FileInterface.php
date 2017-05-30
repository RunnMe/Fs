<?php

namespace Runn\Fs;

/**
 * Common interface for all files, dirs and links
 *
 * Interface FileInterface
 * @package Runn\Fs
 */
interface FileInterface
    extends PathAwareInterface
{

    /**
     * @return bool
     */
    public function exists(): bool;

    /**
     * @return bool
     */
    public function isFile(): bool;

    /**
     * @return bool
     */
    public function isDir(): bool;

    /**
     * @return bool
     */
    public function isLink(): bool;

    /**
     * @return bool
     */
    public function isReadable(): bool;

    /**
     * @return bool
     */
    public function isWritable(): bool;

    /**
     * @param \DateTimeInterface|int|null $time
     * @return $this
     */
    public function touch($time = null);

    /**
     * @return $this
     */
    public function create();

    /**
     * @param bool $clearstatcache
     * @return int
     */
    public function mtime($clearstatcache = true);

}