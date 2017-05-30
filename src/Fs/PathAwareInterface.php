<?php

namespace Runn\Fs;

/**
 * Class PathAwareInterface
 * @package Runn\Fs
 */
interface PathAwareInterface
{

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path);

    /**
     * @return string|null
     */
    public function getPath()/*: ?string*/;

}