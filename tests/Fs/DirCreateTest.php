<?php

namespace Runn\tests\Fs\Dir;

use Runn\Fs\Dir;
use Runn\Fs\Exceptions\FileAlreadyExists;

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

    /**
     * @expectedException \Runn\Fs\Exceptions\DirAlreadyExists
     */
    public function testCreateAlreadyExists()
    {
        $dirname = sys_get_temp_dir() . '/FsTest_create';
        mkdir($dirname);

        try {
            $dir = new Dir($dirname);
            $dir->create();
        } catch (FileAlreadyExists $e) {
            return;
        } finally {
            rmdir($dirname);
        }

        $this->fail();
    }

    public function testCreateValid()
    {
        $dirname = sys_get_temp_dir() . '/FsTest_create';
        if (file_exists($dirname)) {
            rmdir($dirname);
        }
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
        $dirname = sys_get_temp_dir() . '/FsTest_create';
        if (file_exists($dirname)) {
            rmdir($dirname);
        }
        $this->assertDirectoryNotExists($dirname);

        $dir = new Dir($dirname, 0777);
        $ret = $dir->create();

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($dirname);

        /** @todo @7.2 PHP_OS_FAMILY  != 'Windows' */
        if (!in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            $this->assertEquals('0777', substr(sprintf('%o', fileperms($dirname)), -4));
        }

        rmdir($dirname);
    }

}