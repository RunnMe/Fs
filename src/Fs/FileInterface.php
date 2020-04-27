<?php

namespace Runn\Fs;

/**
 * Common interface for all files, directories and links
 *
 * Interface FileInterface
 * @package Runn\Fs
 */
interface FileInterface extends PathAwareInterface
{

    /**
     * @return string|null
     */
    public function getRealPath(): ?string;

    /**
     * @return string
     */
    public function __toString();

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
     * @return $this
     */
    public function create();

    /**
     * @param \DateTimeInterface|int|null $time
     * @return $this
     */
    public function touch($time = null);

    /**
     * @param bool $clearstatcache
     * @return int
     */
    public function mtime($clearstatcache = true);

    /**
     * @param \Runn\Fs\Dir $dir
     * @param string|null $targetName
     * @return self
     */
    public function linkInto(Dir $dir, string $targetName = null): FileInterface;

    /**
     * @param \Runn\Fs\Dir $dir
     * @param string|null $targetName
     * @return self
     */
    public function copyInto(Dir $dir, string $targetName = null): FileInterface;

}
