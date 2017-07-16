<?php

namespace Runn\tests\Fs\Dir;

use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirNotReadable;

class DirMtimeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testMtimeEmptyPath()
    {
        $dir = new Dir();
        $dir->mtime();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\DirNotExists
     */
    public function testMtimeNotExistingPath()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $dir = new Dir($path);
        $dir->mtime();
    }

    public function testMtimeNotReadable()
    {
        /** @todo @7.2 PHP_OS_FAMILY  == 'Windows' */
        if (in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            return;
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        try {

            mkdir($path, 0000);
            chmod($path, 0000);
            $dir = new Dir($path);
            $this->assertFalse($dir->isReadable());

            $dir->mtime();

        } catch (DirNotReadable $e) {
            return;
        } finally {
            chmod($path, 0777);
            rmdir($path);
        }
    }

    public function testMtimeEmptyDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $dir = new Dir($path);
        mkdir($path);
        $this->assertTrue($dir->exists());
        $this->assertEquals(time(), $dir->mtime(), '', 1);
        $this->assertEquals(time(), $dir->mtime(true, true), '', 1);

        touch($path, time()-1000);
        $this->assertEquals(time()-1000, $dir->mtime(), '', 1);
        $this->assertEquals(time()-1000, $dir->mtime(true, true), '', 1);

        rmdir($path);
    }

    public function testMtimeOnlyDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        touch($path . '/test.txt');

        $dir = new Dir($path);
        $this->assertTrue($dir->exists());
        $this->assertEquals(time(), $dir->mtime(), '', 1);
        $this->assertEquals(time(), $dir->mtime(true, true), '', 1);

        touch($path, time()-1000);
        $this->assertEquals(time(), $dir->mtime(), '', 1);
        $this->assertEquals(time()-1000, $dir->mtime(true, true), '', 1);

        unlink($path . '/test.txt');
        rmdir($path);
    }

    public function testMtimeNotEmptyDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        mkdir($path . '/1');
        mkdir($path . '/2');
        touch($path . '/3');
        touch($path . '/4');

        $dir = new Dir($path);
        $this->assertEquals(time(), $dir->mtime(), '', 1);
        $this->assertEquals(time(), $dir->mtime(true, true), '', 1);

        touch($path, time() - 1000);
        touch($path, time()-1000);
        $this->assertEquals(time(), $dir->mtime(), '', 1);
        $this->assertEquals(time()-1000, $dir->mtime(true, true), '', 1);

        touch($path . '/1', time()-10);
        touch($path . '/2', time()-20);
        touch($path . '/3', time()-30);
        touch($path . '/4', time()-40);

        $this->assertEquals(time()-10, $dir->mtime(), '', 1);
        $this->assertEquals(time()-1000, $dir->mtime(true, true), '', 1);

        touch($path . '/1', time()-100);
        $this->assertEquals(time()-20, $dir->mtime(), '', 1);
        $this->assertEquals(time()-1000, $dir->mtime(true, true), '', 1);

        unlink($path . '/4');
        unlink($path . '/3');
        rmdir($path . '/2');
        rmdir($path . '/1');
        rmdir($path);
    }

}