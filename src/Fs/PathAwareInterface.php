<?php

namespace Runn\Fs;

/**
 * Interface PathAwareInterface
 * @package Runn\Fs
 */
interface PathAwareInterface
{

    /**
     * Sets the path (for example path in filesystem)
     * If prefix is set, the full path will be set as prefix and path concatenated
     *
     * @param string $path
     * @param string $prefix
     * @return $this
     */
    public function setPath(string $path, string $prefix = '');

    /**
     * Returns the path
     * If prefix is set returns a relative path based on prefix
     *
     * @param string $prefix
     * @return string
     */
    public function getPath(string $prefix = ''): string;

}
