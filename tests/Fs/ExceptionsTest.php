<?php

namespace Runn\tests\Fs\Exception;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exception;
use Runn\Fs\Exceptions\{CopyError,
    DirAlreadyExists,
    DirNotDeletable,
    DirNotExists,
    DirNotReadable,
    EmptyPath,
    FileAlreadyExists,
    FileNotDeletable,
    FileNotExists,
    FileNotReadable,
    FileNotWritable,
    FileNullContents,
    InvalidDir,
    InvalidFile,
    InvalidFileClass,
    MkDirError,
    SymlinkError};

class ExceptionsTest extends TestCase
{

    public function testCopyError()
    {
        $exception = new CopyError();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testDirAlreadyExists()
    {
        $exception = new DirAlreadyExists();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileAlreadyExists::class, $exception);
    }

    public function testDirNotDeletable()
    {
        $exception = new DirNotDeletable();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileNotDeletable::class, $exception);
    }

    public function testDirNotExists()
    {
        $exception = new DirNotExists();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileNotExists::class, $exception);
    }

    public function testDirNotReadable()
    {
        $exception = new DirNotReadable();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(FileNotReadable::class, $exception);
    }

    public function testEmptyPath()
    {
        $exception = new EmptyPath();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testFileAlreadyExists()
    {
        $exception = new FileAlreadyExists();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testFileNotDeletable()
    {
        $exception = new FileNotDeletable();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testFileNotExists()
    {
        $exception = new FileNotExists();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testFileNotReadable()
    {
        $exception = new FileNotReadable();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testFileNotWritable()
    {
        $exception = new FileNotWritable();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testFileNullContents()
    {
        $exception = new FileNullContents();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testInvalidDir()
    {
        $exception = new InvalidDir();
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertInstanceOf(InvalidFile::class, $exception);
    }

    public function testInvalidFile()
    {
        $exception = new InvalidFile();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testInvalidFileClass()
    {
        $exception = new InvalidFileClass();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testMkDirError()
    {
        $exception = new MkDirError();
        $this->assertInstanceOf(Exception::class, $exception);
    }

    public function testSymlinkError()
    {
        $exception = new SymlinkError();
        $this->assertInstanceOf(Exception::class, $exception);
    }

}
