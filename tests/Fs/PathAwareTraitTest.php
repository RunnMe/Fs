<?php

namespace Runn\tests\Fs\PathAwareTrait;

use PHPUnit\Framework\TestCase;
use Runn\Fs\PathAwareInterface;
use Runn\Fs\PathAwareTrait;

class PathAwareTraitTest extends TestCase
{

    public function testSetPath()
    {
        $obj = new class implements PathAwareInterface {
            use PathAwareTrait;
        };

        $reflector = new \ReflectionProperty(get_class($obj), 'path');
        $reflector->setAccessible(true);

        $this->assertSame('', $reflector->getValue($obj));

        $obj->setPath('foo');
        $this->assertSame('foo', $reflector->getValue($obj));

        $obj->setPath('bar', '');
        $this->assertSame('bar', $reflector->getValue($obj));

        $obj->setPath('foo', 'bar/');
        $this->assertSame('bar/foo', $reflector->getValue($obj));
    }

    public function testGetPath()
    {
        $obj = new class implements PathAwareInterface {
            use PathAwareTrait;
        };

        $this->assertSame('', $obj->getPath());

        $obj->setPath('foo/bar');
        $this->assertSame('foo/bar', $obj->getPath());
        $this->assertSame('bar', $obj->getPath('foo/'));
        $this->assertSame('foo/bar', $obj->getPath('baz'));
    }

}
