<?php

namespace Runn\tests\Fs\Dir;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirAlreadyExists;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\MkDirError;

class DirCreateTest extends TestCase
{

    public function testCreateEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $dir = new Dir();
        $dir->create();
    }

    public function testCreateAlreadyExists()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        mkdir($path);

        try {
            $dir = new Dir($path);
            $dir->create();
            $this->fail();
        } catch (DirAlreadyExists $e) {
            $this->assertTrue(true);
        } finally {
            rmdir($path);
        }

    }

    public function testCreateMkDirError()
    {
        if (\Runn\Fs\isWindows()) {
            return;
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        mkdir($path . '/1/2', 0777, true);
        chmod($path . '/1/2', 0000);
        $this->assertTrue(file_exists($path . '/1/2'));
        $this->assertTrue(is_dir($path . '/1/2'));
        $this->assertFalse(is_writable($path . '/1/2'));

        try {

            $dir = new Dir($path . '/1/2/3');
            $dir->create();

        } catch (MkDirError $e) {
            return;
        } finally {
            chmod($path . '/1/2', 0777);
            rmdir($path . '/1/2');
            rmdir($path . '/1');
            rmdir($path);
        }
    }

    public function testCreateValid()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $dir = new Dir($path);
        $ret = $dir->create();

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);

        if (!\Runn\Fs\isWindows()) {
            $this->assertEquals('0755', substr(sprintf('%o', fileperms($path)), -4));
        }

        rmdir($path);
    }

    public function testCreateValidWithMode()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $dir = new Dir($path);
        $ret = $dir->create(0777);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);

        if (!\Runn\Fs\isWindows()) {
            $this->assertEquals('0777', substr(sprintf('%o', fileperms($path)), -4));
        }

        rmdir($path);
    }

    public function testMakeAlreadyExists()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        mkdir($path);

        $dir = new Dir($path);
        $this->assertTrue($dir->exists());

        $ret = $dir->make();
        $this->assertSame($dir, $ret);

        rmdir($path);
    }

    public function testMake()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $dir = new Dir($path);
        $ret = $dir->make(0777);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);

        if (!\Runn\Fs\isWindows()) {
            $this->assertEquals('0777', substr(sprintf('%o', fileperms($path)), -4));
        }

        rmdir($path);
    }

}
