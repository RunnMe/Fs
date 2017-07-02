<?php

namespace Runn\tests\Fs\Exception;

use Runn\Fs\Exception;
use Runn\Fs\Exceptions\DirIsFile;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileIsDir;
use Runn\Fs\Exceptions\FileNotDeletable;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;

class ExceptionsTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyPath()
    {
        $exception = new EmptyPath();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['EMPTY_PATH']);
    }

    public function testFileIsDir()
    {
        $exception = new FileIsDir();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['FILE_IS_DIR']);
    }

    public function testFileNotDeletable()
    {
        $exception = new FileNotDeletable();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['FILE_NOT_DELETABLE']);
    }

    public function testFileAlreadyExists()
    {
        $exception = new FileAlreadyExists();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['FILE_ALREADY_EXISTS']);
    }

    public function testFileNotExists()
    {
        $exception = new FileNotExists();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['FILE_NOT_EXISTS']);
    }

    public function testFileNotReadable()
    {
        $exception = new FileNotReadable();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['FILE_NOT_READABLE']);
    }

    public function testFileNotWritable()
    {
        $exception = new FileNotWritable();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['FILE_NOT_WRITABLE']);
    }

    public function testDirIsFile()
    {
        $exception = new DirIsFile();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['DIR_IS_FILE']);
    }

}