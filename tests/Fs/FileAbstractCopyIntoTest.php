<?php

namespace Runn\tests\Fs\FileAbstract;

use function Runn\Fs\canXcopy;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\CopyError;
use Runn\Fs\FileAbstract;

class FakeFileCopyToClass extends FileAbstract
{
    public function create() {
        return $this;
    }
    public function mtime($clearstatcache = true) {
        return 0;
    }
}

class FileAbstractCopyIntoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testSourceEmptyPath()
    {
        $source = new FakeFileCopyToClass();
        $source->copyInto(new Dir(__DIR__));
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testSourceNotExists()
    {
        $source = new FakeFileCopyToClass(__FILE__ . uniqid());
        $source->copyInto(new Dir(__DIR__));
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testTargetEmptyPath()
    {
        $source = new FakeFileCopyToClass(__FILE__);
        $source->copyInto(new Dir());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\DirNotExists
     */
    public function testTargetNotExists()
    {
        $source = new FakeFileCopyToClass(__FILE__);
        $source->copyInto(new Dir(__DIR__ . uniqid()));
    }

    public function testSourceIsFileTargetIsDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsCopyTest');
        $target = $path . DIRECTORY_SEPARATOR . 'test.file';
        mkdir($target, 0777, true);

        $src = __DIR__ . DIRECTORY_SEPARATOR . 'test.file';
        touch($src);

        try {
            $file = new FakeFileCopyToClass($src);
            $file->copyInto(new Dir($path));
        } catch (CopyError $e) {
            $this->assertEquals('Target exists and is dir instead of file', $e->getMessage());
            return;
        } finally {

            unlink($src);
            rmdir($target);
            rmdir($path);

        }

        $this->fail();
    }

    public function testSourceIsDirTargetIsFile()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsCopyTest');
        $target = $path . DIRECTORY_SEPARATOR . 'test.dir';
        mkdir($path);
        touch($target);

        $src = __DIR__ . DIRECTORY_SEPARATOR . 'test.dir';
        mkdir($src);

        try {
            $dir = new FakeFileCopyToClass($src);
            $dir->copyInto(new Dir($path));
        } catch (CopyError $e) {
            $this->assertEquals('Target exists and is file instead of dir', $e->getMessage());
            return;
        } finally {

            rmdir($src);
            unlink($target);
            rmdir($path);

        }

        $this->fail();
    }

    public function testCopyFileWithOwnName()
    {
        $src = __DIR__ . DIRECTORY_SEPARATOR . 'test.file';
        touch($src);

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsCopyTest');
        $target = $path . DIRECTORY_SEPARATOR . 'test.dir';
        mkdir($target, 0777, true);

        try {

            $file = new FakeFileCopyToClass($src);
            $file->copyInto(new Dir($target));

            $this->assertFileExists($target . DIRECTORY_SEPARATOR . 'test.file');

        } catch (\Throwable $e) {
            $this->fail();
        } finally {
            unlink($target . DIRECTORY_SEPARATOR . 'test.file');
            rmdir($target);
            rmdir($path);
            unlink($src);
        }
    }

    public function testCopyFileWithAnotherName()
    {
        $src = __DIR__ . DIRECTORY_SEPARATOR . 'test.file';
        touch($src);

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsCopyTest');
        $target = $path . DIRECTORY_SEPARATOR . 'test.dir';
        mkdir($target, 0777, true);

        try {

            $file = new FakeFileCopyToClass($src);
            $file->copyInto(new Dir($target), 'foo.test');

            $this->assertFileExists($target . DIRECTORY_SEPARATOR . 'foo.test');

        } catch (\Throwable $e) {
            $this->fail();
        } finally {
            unlink($target . DIRECTORY_SEPARATOR . 'foo.test');
            rmdir($target);
            rmdir($path);
            unlink($src);
        }
    }

    /*
    public function testXcopyFile()
    {
        if (!canXcopy()) {
            return;
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsCopyTest');
        $target1 = $path . DIRECTORY_SEPARATOR . basename(__FILE__);
        $target2 = $path . DIRECTORY_SEPARATOR . 'test.file';
        mkdir($path);

        $file = new FakeFileCopyToClass(__FILE__);

        $file->copyInto(new Dir($path));
        $this->assertFileEquals(__FILE__, $target1);

        $file->copyInto(new Dir($path), 'test.file');
        $this->assertFileEquals(__FILE__, $target2);

        unlink($target2);
        unlink($target1);
        rmdir($path);
    }
    */

}