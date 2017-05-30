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

    protected $path = null;

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath()/*: ?string*/
    {
        return $this->path;
    }

}