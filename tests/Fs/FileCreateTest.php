<?php

namespace Runn\tests\Fs\File;

use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\File;

class FileCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testCreateEmptyPath()
    {
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
            return;
        } finally {
            unlink($filename);
        }

        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotWritable
     */
    public function testCreateNotWritable()
    {
        $filename = sys_get_temp_dir() . '/Some/Fake/Dir/Which/Is/Not/Exist/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $file = new File($filename);
        $file->create();
    }

}