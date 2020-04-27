<?php

namespace Runn\tests\Fs\File;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\File;

class FileTouchTest extends TestCase
{

    public function testTouchEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new File();
        $file->touch();
    }

    public function testTouchEmptyTime()
    {
        $filename = sys_get_temp_dir() . '/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $this->assertFileNotExists($filename);

        $file = new File($filename);
        $ret = $file->touch();

        $this->assertSame($ret, $file);
        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEqualsWithDelta(time(), filemtime($filename), 1);

        touch($filename, time()-1000);
        clearstatcache($filename);
        $this->assertEqualsWithDelta(time()-1000, filemtime($filename), 1);

        $file->touch();

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEqualsWithDelta(time(), filemtime($filename), 1);

        unlink($filename);
    }

    public function testTouchUnixTime()
    {
        $filename = sys_get_temp_dir() . '/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $this->assertFileNotExists($filename);

        $file = new File($filename);
        $time = time() - 10;
        $file->touch($time);

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEqualsWithDelta($time, filemtime($filename), 1);

        $file->touch($time+2);

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEqualsWithDelta($time+2, filemtime($filename), 1);

        unlink($filename);
    }

    public function testTouchDateTime()
    {
        $filename = sys_get_temp_dir() . '/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $this->assertFileNotExists($filename);

        $file = new File($filename);
        $time = (new \DateTime())->sub(new \DateInterval('PT10S'));
        $file->touch($time);

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEqualsWithDelta($time->getTimestamp(), filemtime($filename), 1);

        $time->add(new \DateInterval('PT2S'));
        $file->touch($time);

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEqualsWithDelta($time->getTimestamp(), filemtime($filename), 1);

        unlink($filename);
    }

    public function testTouchNotWritable()
    {
        $filename = sys_get_temp_dir() . '/Some/Fake/Dir/Which/Is/Not/Exist/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $this->expectException(FileNotWritable::class);
        $file = new File($filename);
        $file->touch();
    }

}
