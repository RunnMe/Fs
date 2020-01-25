<?php

namespace Runn\tests\Fs\Exception;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exception;
use Runn\Fs\Exceptions\CopyError;
use Runn\Fs\Exceptions\DirAlreadyExists;
use Runn\Fs\Exceptions\DirIsFile;
use Runn\Fs\Exceptions\DirNotExists;
use Runn\Fs\Exceptions\DirNotReadable;
use Runn\Fs\Exceptions\DirTouchError;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileAlreadyExists;
use Runn\Fs\Exceptions\FileNotDeletable;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\Exceptions\InvalidDir;
use Runn\Fs\Exceptions\InvalidFile;
use Runn\Fs\Exceptions\InvalidFileClass;
use Runn\Fs\Exceptions\MkDirError;
use Runn\Fs\Exceptions\SymlinkError;

class ExceptionsTest extends TestCase
{

    public function testInvalidFileClass()
    {
        $exception = new InvalidFileClass();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['INVALID_FILE_CLASS']);
    }

    public function testEmptyPath()
    {
        $exception = new EmptyPath();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['EMPTY_PATH']);
    }

    public function testSymlinkError()
    {
        $exception = new SymlinkError();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['SYMLINK_ERROR']);
    }

    public function testCopyError()
    {
        $exception = new CopyError();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['COPY_ERROR']);
    }

    public function testInvalidFile()
    {
        $exception = new InvalidFile();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['INVALID_FILE']);
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

    public function testInvalidDir()
    {
        $exception = new InvalidDir();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidFile::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['INVALID_DIR']);
    }

    public function testMkDirError()
    {
        $exception = new MkDirError();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['MKDIR_ERROR']);
    }

    public function testDirAlreadyExists()
    {
        $exception = new DirAlreadyExists();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileAlreadyExists::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['DIR_ALREADY_EXISTS']);
    }

    public function testDirNotExists()
    {
        $exception = new DirNotExists();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileNotExists::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['DIR_NOT_EXISTS']);
    }

    public function testDirTouchError()
    {
        $exception = new DirTouchError();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['DIR_TOUCH_ERROR']);
    }

    public function testDirNotReadable()
    {
        $exception = new DirNotReadable();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileNotReadable::class, $exception);
        $this->assertSame($exception->getCode(), Exception::CODES['DIR_NOT_READABLE']);
    }

}
