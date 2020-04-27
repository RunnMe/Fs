<?php

namespace Runn\tests\Fs\Dir;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirNotExists;
use Runn\Fs\Exceptions\EmptyPath;

class DirTouchTest extends TestCase
{

    public function testTouchEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $dir = new Dir();
        $dir->touch();
    }

    public function testTouchDirNotExists()
    {
        $path = sys_get_temp_dir() . '/FsDirTest_touch';

        $this->expectException(DirNotExists::class);
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
        $this->assertEqualsWithDelta(time()-1000, filemtime($path), 1);

        $dir = new Dir($path);
        $time = time();
        $ret = $dir->touch();

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEqualsWithDelta($time, filemtime($path), 1);

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
        $this->assertEqualsWithDelta(time()-1000, filemtime($path), 1);

        $dir = new Dir($path);
        $time = time() - 10;
        $ret = $dir->touch($time);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEqualsWithDelta($time, filemtime($path), 1);

        $ret = $dir->touch($time+2);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEqualsWithDelta($time+2, filemtime($path), 1);

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
        $this->assertEqualsWithDelta(time()-1000, filemtime($path), 1);

        $dir = new Dir($path);
        $time = (new \DateTime())->sub(new \DateInterval('PT10S'));
        $ret = $dir->touch($time);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEqualsWithDelta($time->getTimestamp(), filemtime($path), 1);

        $time->add(new \DateInterval('PT2S'));
        $ret = $dir->touch($time);

        $this->assertSame($ret, $dir);
        $this->assertDirectoryExists($path);
        clearstatcache();
        $this->assertEqualsWithDelta($time->getTimestamp(), filemtime($path), 1);

        rmdir($path);
    }

}
