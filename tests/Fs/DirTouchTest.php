<?php

namespace Runn\tests\Fs\Dir;

use Runn\Fs\Dir;
use Runn\Fs\File;

class DirTouchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testTouchEmptyPath()
    {
        $dir = new Dir();
        $dir->touch();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\DirNotExists
     */
    public function testTouchDirNotExists()
    {
        $path = sys_get_temp_dir() . '/FsDirTest_touch';
        $dir = new Dir($path);
        $dir->touch();
    }

    public function testTouchEmptyTime()
    {
        $path = sys_get_temp_dir() . '/FsDirTest_touch';
        if (file_exists($path)) {
            rmdir($path);
        }
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        touch($path, time()-1000);
        clearstatcache($path);
        $this->assertDirectoryExists($path);
        $this->assertEquals(time()-1000, filemtime($path), '', 1);

        $dir = new Dir($path);
        $time = time();
        $ret = $dir->touch();

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEquals($time, filemtime($path), '', 1);

        rmdir($path);
    }

    public function testTouchUnixTime()
    {
        $path = sys_get_temp_dir() . '/FsDirTest_touch';
        if (file_exists($path)) {
            rmdir($path);
        }
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        touch($path, time()-1000);
        clearstatcache($path);
        $this->assertDirectoryExists($path);
        $this->assertEquals(time()-1000, filemtime($path), '', 1);

        $dir = new Dir($path);
        $time = time() - 10;
        $ret = $dir->touch($time);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEquals($time, filemtime($path), '', 1);

        $ret = $dir->touch($time+2);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEquals($time+2, filemtime($path), '', 1);

        rmdir($path);
    }

    public function testTouchDateTime()
    {
        $path = sys_get_temp_dir() . '/FsDirTest_touch';
        if (file_exists($path)) {
            rmdir($path);
        }
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        touch($path, time()-1000);
        clearstatcache($path);
        $this->assertDirectoryExists($path);
        $this->assertEquals(time()-1000, filemtime($path), '', 1);

        $dir = new Dir($path);
        $time = (new \DateTime())->sub(new \DateInterval('PT10S'));
        $ret = $dir->touch($time);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEquals($time->getTimestamp(), filemtime($path), '', 1);

        $time->add(new \DateInterval('PT2S'));
        $ret = $dir->touch($time);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEquals($time->getTimestamp(), filemtime($path), '', 1);

        rmdir($path);
    }

}