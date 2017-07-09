<?php

namespace Runn\Fs;

use Runn\Core\TypedCollection;

/**
 * Typed collection of files (dirs, links)
 *
 * Class FileCollection
 * @package Runn\Fs
 */
class FileCollection
    extends TypedCollection
{

    public static function getType()
    {
        return FileAbstract::class;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getPaths($prefix = '')
    {
        return $this->collect(function (FileAbstract $file) use ($prefix) {
            return $file->getPath($prefix);
        });
    }

}