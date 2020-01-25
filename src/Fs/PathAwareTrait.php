<?php

namespace Runn\Fs;

/**
 * Trait PathAwareTrait
 * @package Runn\Fs
 *
 * @implements \Runn\Fs\PathAwareInterface
 */
trait PathAwareTrait
    // implements PathAwareInterface
{

    /** @var string $path */
    protected $path = '';

    /**
     * @param string $path
     * @param string $prefix
     * @return $this
     */
    public function setPath(string $path, string $prefix = '')
    {
        $this->path = $prefix . $path;
        return $this;
    }

    /**
     * @param string $prefix
     * @return string
     */
    public function getPath(string $prefix = ''): string
    {
        if (!empty($prefix) && 0 === strpos($this->path, $prefix)) {
            return substr($this->path, strlen($prefix));
        }
        return $this->path;
    }

}
