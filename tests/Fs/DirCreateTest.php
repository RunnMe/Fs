<?php

namespace Runn\tests\Fs\Dir;

use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirAlreadyExists;
use Runn\Fs\Exceptions\MkDirError;

class DirCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testCreateEmptyPath()
    {
        $dir = new Dir();
        $dir->create();
    }

    public function testCreateAlreadyExists()
    {
        $dirname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        mkdir($dirname);

        try {
            $dir = new Dir($dirname);
            $dir->create();
        } catch (DirAlreadyExists $e) {
            return;
        } finally {
            rmdir($dirname);
        }

        $this->fail();
    }

    public function testCreateMkDirError()
    {
        /** @todo @7.2 PHP_OS_FAMILY  != 'Windows' */
        if (in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            return;
        }

        $dirname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($dirname);

        mkdir($dirname . '/1/2', 0777, true);
        chmod($dirname . '/1/2', 0000);
        $this->assertTrue(file_exists($dirname . '/1/2'));
        $this->assertTrue(is_dir($dirname . '/1/2'));
        $this->assertFalse(is_writable($dirname . '/1/2'));

        try {

            $dir = new Dir($dirname . '/1/2/3');
            $dir->create();

        } catch (MkDirError $e) {
            return;
        } finally {
            chmod($dirname . '/1/2', 0777);
            rmdir($dirname . '/1/2');
            rmdir($dirname . '/1');
            rmdir($dirname);
        }
    }

    public function testCreateValid()
    {
        $dirname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($dirname);

        $dir = new Dir($dirname);
        $ret = $dir->create();

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($dirname);

        /** @todo @7.2 PHP_OS_FAMILY  != 'Windows' */
        if (!in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            $this->assertEquals('0755', substr(sprintf('%o', fileperms($dirname)), -4));
        }

        rmdir($dirname);
    }

    public function testCreateValidWithMode()
    {
        $dirname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($dirname);

        $dir = new Dir($dirname);
        $ret = $dir->create(0777);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($dirname);

        /** @todo @7.2 PHP_OS_FAMILY  != 'Windows' */
        if (!in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            $this->assertEquals('0777', substr(sprintf('%o', fileperms($dirname)), -4));
        }

        rmdir($dirname);
    }

}