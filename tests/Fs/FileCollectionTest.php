<?php

namespace Runn\tests\Fs\FileCollection;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\File;
use Runn\Fs\FileAbstract;
use Runn\Fs\FileCollection;

class FileCollectionTest extends TestCase
{

    public function testGetType()
    {
        $this->assertSame(FileAbstract::class, FileCollection::getType());
    }

    public function testGetPaths()
    {
        $collection = new FileCollection;

        $this->assertSame([], $collection->getPaths());
        $this->assertSame([], $collection->getPaths('foo'));

        $collection[] = new File('foo/1/1');
        $collection[] = new Dir('foo/1/2');

        $this->assertSame(['foo/1/1', 'foo/1/2'], $collection->getPaths());
        $this->assertSame(['/1/1', '/1/2'], $collection->getPaths('foo'));
    }

}
