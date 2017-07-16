<?php

namespace Runn\tests\Fs\File;

use Runn\Fs\File;

class FileTouchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testTouchEmptyPath()
    {
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
        $this->assertEquals(time(), filemtime($filename), '', 1);

        touch($filename, time()-1000);
        clearstatcache($filename);
        $this->assertEquals(time()-1000, filemtime($filename), '', 1);

        $file->touch();

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEquals(time(), filemtime($filename), '', 1);

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
        $this->assertEquals($time, filemtime($filename), '', 1);

        $file->touch($time+2);

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEquals($time+2, filemtime($filename), '', 1);

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
        $this->assertEquals($time->getTimestamp(), filemtime($filename), '', 1);

        $time->add(new \DateInterval('PT2S'));
        $file->touch($time);

        $this->assertFileExists($filename);
        clearstatcache();
        $this->assertEquals($time->getTimestamp(), filemtime($filename), '', 1);

        unlink($filename);
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotWritable
     */
    public function testTouchNotWritable()
    {
        $filename = sys_get_temp_dir() . '/Some/Fake/Dir/Which/Is/Not/Exist/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $file = new File($filename);
        $file->touch();
    }

}