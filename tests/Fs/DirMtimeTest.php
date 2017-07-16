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
        $path = sys_get_temp_dir() . '/FsDirTest_mtime';
        if (file_exists($path)) {
            rmdir($path);
        }
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

        $path = sys_get_temp_dir() . '/FsDirTest_mtime';
        if (file_exists($path)) {
            rmdir($path);
        }
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

}