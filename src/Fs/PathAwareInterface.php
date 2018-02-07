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
     * @param string $prefix
     * @return $this
     */
    public function setPath(string $path, string $prefix = '');

    /**
     * @param string $prefix
     * @return string
     */
    public function getPath(string $prefix = ''): string;

}