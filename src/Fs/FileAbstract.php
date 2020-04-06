<?php

namespace Runn\Fs;

use Runn\Fs\Exceptions\CopyError;
use Runn\Fs\Exceptions\DirNotExists;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\Exceptions\InvalidFileClass;
use Runn\Fs\Exceptions\SymlinkError;

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

        if (file_exists($path) && is_dir($path)) {
            $class = $class ?? Dir::class;
        } else {
            $class = $class ?? File::class;
        }

        return new $class($path);
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
     * @return string
     */
    public function __toString()
    {
        return $this->getPath() ?? '';
    }

    /**
     * @return string|null
     */
    public function getRealPath(): ?string
    {
        if (empty($this->getPath())) {
            return null;
        }
        return realpath($this->getPath()) ?: null;
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
     * Creates file or link or directory if one does not exist
     * @return $this
     */
    abstract public function create();

    /**
     * Deletes file or link or directory - recursively
     * @return $this
     */
    abstract public function delete();

    /**
     * @param \DateTimeInterface|int|null $time
     * @return $this
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\DirNotExists
     * @throws \Runn\Fs\Exceptions\FileNotWritable
     */
    public function touch($time = null)
    {
        if (empty($this->getPath())) {
            throw new EmptyPath;
        }

        if ( ($this instanceof Dir) && !$this->exists() ) {
            throw new DirNotExists;
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
     * @param \Runn\Fs\Dir $dir
     * @param string|null $targetName
     * @return \Runn\Fs\FileInterface
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     * @throws \Runn\Fs\Exceptions\DirNotExists
     * @throws \Runn\Fs\Exceptions\SymlinkError
     */
    public function linkInto(Dir $dir, string $targetName = null): FileInterface
    {
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        if (!$dir->exists()) {
            throw new DirNotExists;
        }
        $targetName = $targetName ?: basename($this->getPath());
        $targetPath = $dir->getPath() . DIRECTORY_SEPARATOR . $targetName;

        if (file_exists($targetPath) && !is_link($targetPath)) {
            throw new SymlinkError;
        }

        $res = @symlink($this->getPath(), $targetPath);
        if (false === $res) {
            throw new SymlinkError;
        }

        return self::instance($targetPath);
    }

    /**
     * @param \Runn\Fs\Dir $dir
     * @param string|null $targetName
     * @return \Runn\Fs\FileInterface
     * @throws \Runn\Fs\Exceptions\EmptyPath
     * @throws \Runn\Fs\Exceptions\FileNotExists
     * @throws \Runn\Fs\Exceptions\DirNotExists
     * @throws \Runn\Fs\Exceptions\CopyError
     */
    public function copyInto(Dir $dir, string $targetName = null): FileInterface
    {
        if (!$this->exists()) {
            throw new FileNotExists;
        }
        if (!$dir->exists()) {
            throw new DirNotExists;
        }

        $targetName = $targetName ?: basename($this->getPath());
        $targetPath = $dir->getPath() . DIRECTORY_SEPARATOR . $targetName;

        if (file_exists($targetPath)) {
            if ($this->isFile() && is_dir($targetPath)) {
                throw new CopyError('Target exists and is dir instead of file');
            }
            if ($this->isDir() && is_file($targetPath)) {
                throw new CopyError('Target exists and is file instead of dir');
            }
        }

        $res = \Runn\Fs\copy($this->getPath(), $targetPath);
        // @codeCoverageIgnoreStart
        if (false === $res) {
            throw new CopyError('PHP "copy" error');
        }
        // @codeCoverageIgnoreEnd

        return self::instance($targetPath);
    }

    /**
     * @param bool $clearstatcache
     * @return int
     */
    abstract public function mtime($clearstatcache = true);

}