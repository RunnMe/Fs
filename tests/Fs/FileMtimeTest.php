<?php

namespace Runn\tests\Fs\File;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\File;

class FileMtimeTest extends TestCase
{

    public function testMtimeEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new File();
        $file->mtime();
    }

    public function testMtimeNotExistingPath()
    {
        $filename = sys_get_temp_dir() . '/FsTest_mtime';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $this->assertFileNotExists($filename);

        $this->expectException(FileNotExists::class);
        $file = new File($filename);
        $file->mtime();
    }

    public function testMtimeNotReadable()
    {
        if (\Runn\Fs\isWindows()) {
            $this->assertTrue(true);
            return;
        }

        $filename = tempnam(sys_get_temp_dir(), 'FsTest_mtime');

        try {

            chmod($filename, 0000);
            $file = new File($filename);
            $this->assertFalse($file->isReadable());

            $file->mtime();

        } catch (FileNotReadable $e) {
            $this->assertTrue(true);
            return;
        } finally {
            chmod($filename, 0777);
            unlink($filename);
        }
    }

    public function testFileMtime()
    {
        $filename = sys_get_temp_dir() . '/FsTest_mtime';
        $file = new File($filename);
        $time = time();
        $file->touch($time);

        $this->assertSame($time, $file->mtime());
        $this->assertSame($time, $file->mtime(false));

        sleep(1);
        file_put_contents($filename, 'Test');
        $this->assertSame($time+1, $file->mtime());

        @unlink($filename);
    }
}
