<?php

namespace Runn\tests\Fs\FileAbstract;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirNotExists;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\SymlinkError;
use Runn\Fs\File;
use Runn\Fs\FileAbstract;

class FakeFileLinkToClass extends FileAbstract
{
    public function create() {
        return $this;
    }
    public function delete() {
        return $this;
    }
    public function mtime($clearstatcache = true) {
        return 0;
    }
}

class FileAbstractLinkIntoTest extends TestCase
{

    public function testSourceEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $source = new FakeFileLinkToClass();
        $source->linkInto(new Dir(__DIR__));
    }

    public function testSourceNotExists()
    {
        $this->expectException(FileNotExists::class);
        $source = new FakeFileLinkToClass(__FILE__ . uniqid());
        $source->linkInto(new Dir(__DIR__));
    }

    public function testTargetEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $source = new FakeFileLinkToClass(__FILE__);
        $source->linkInto(new Dir());
    }

    public function testTargetNotExists()
    {
        $this->expectException(DirNotExists::class);
        $source = new FakeFileLinkToClass(__FILE__);
        $source->linkInto(new Dir(__DIR__ . uniqid()));
    }

    public function testTargetExistsAndNotLink()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsLinkTest');
        $file = $path . DIRECTORY_SEPARATOR . basename(__FILE__);
        mkdir($path);
        touch($file);

        try {
            $source = new FakeFileLinkToClass(__FILE__);
            $source->linkInto(new Dir($path));
        } catch (SymlinkError $e) {
            $this->assertTrue(true);
            return;
        } finally {
            unlink($file);
            rmdir($path);
        }

        $this->fail();
    }

    public function testSymlinkError()
    {
        if (\Runn\Fs\isWindows()) {
            return;
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsLinkTest');
        mkdir($path);
        chmod($path, 0000);

        try {
            $source = new FakeFileLinkToClass(__FILE__);
            $source->linkInto(new Dir($path));
        } catch (SymlinkError $e) {
            $this->assertTrue(true);
            return;
        } finally {
            chmod($path, 0777);
            rmdir($path);
        }

        $this->fail();
    }

    public function testFileLinkWithoutTargetName()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsLinkTest');
        $target = $path . DIRECTORY_SEPARATOR . basename(__FILE__);
        mkdir($path);

        $source = new FakeFileLinkToClass(__FILE__);
        $ret = $source->linkInto(new Dir($path));

        $this->assertTrue(file_exists($target));
        $this->assertTrue(is_file($target));
        $this->assertTrue(is_link($target));
        $this->assertSame(__FILE__, readlink($target));

        $this->assertInstanceOf(File::class, $ret);
        $this->assertTrue($ret->exists());
        $this->assertTrue($ret->isLink());
        $this->assertTrue($ret->isFile());

        unlink($target);
        rmdir($path);
    }

    public function testFileLinkWithTargetName()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsLinkTest');
        $target = $path . DIRECTORY_SEPARATOR . 'test.file';
        mkdir($path);

        $source = new FakeFileLinkToClass(__FILE__);
        $ret = $source->linkInto(new Dir($path), 'test.file');

        $this->assertTrue(file_exists($target));
        $this->assertTrue(is_file($target));
        $this->assertTrue(is_link($target));
        $this->assertSame(__FILE__, readlink($target));

        $this->assertInstanceOf(File::class, $ret);
        $this->assertTrue($ret->exists());
        $this->assertTrue($ret->isLink());
        $this->assertTrue($ret->isFile());

        unlink($target);
        rmdir($path);
    }

    public function testDirLinkWithoutTargetName()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsLinkTest');
        $target = $path . DIRECTORY_SEPARATOR . basename(__DIR__);
        mkdir($path);

        $source = new FakeFileLinkToClass(__DIR__);
        $ret = $source->linkInto(new Dir($path));

        $this->assertTrue(file_exists($target));
        $this->assertTrue(is_dir($target));
        $this->assertTrue(is_link($target));
        $this->assertSame(__DIR__, readlink($target));

        $this->assertInstanceOf(Dir::class, $ret);
        $this->assertTrue($ret->exists());
        $this->assertTrue($ret->isLink());
        $this->assertTrue($ret->isDir());

        if (\Runn\Fs\isWindows()) {
            rmdir($target);
        } else {
            unlink($target);
        }
        rmdir($path);
    }

    public function testDirLinkWithTargetName()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsLinkTest');
        $target = $path . DIRECTORY_SEPARATOR . 'test.dir';
        mkdir($path);

        $source = new FakeFileLinkToClass(__DIR__);
        $ret = $source->linkInto(new Dir($path), 'test.dir');

        $this->assertTrue(file_exists($target));
        $this->assertTrue(is_dir($target));
        $this->assertTrue(is_link($target));
        $this->assertSame(__DIR__, readlink($target));

        $this->assertInstanceOf(Dir::class, $ret);
        $this->assertTrue($ret->exists());
        $this->assertTrue($ret->isLink());
        $this->assertTrue($ret->isDir());

        if (\Runn\Fs\isWindows()) {
            rmdir($target);
        } else {
            unlink($target);
        }
        rmdir($path);
    }

}
