<?php

namespace Runn\tests\Fs\File;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\File;

class FileCreateTest extends TestCase
{

    public function testCreateEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new File();
        $file->create();
    }

    public function testCreateValid()
    {
        $filename = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertFileNotExists($filename);

        $file = new File($filename);
        $ret = $file->create();

        $this->assertSame($ret, $file);
        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testCreateAlreadyExists()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');

        try {
            $file = new File($filename);
            $file->create();
        } catch (FileAlreadyExists $e) {
            $this->assertTrue(true);
            return;
        } finally {
            unlink($filename);
        }

        $this->fail();
    }

    public function testCreateNotWritable()
    {
        $filename = sys_get_temp_dir() . '/Some/Fake/Dir/Which/Is/Not/Exist/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $this->expectException(FileNotWritable::class);
        $file = new File($filename);
        $file->create();
    }

}
