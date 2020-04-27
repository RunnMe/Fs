<?php

namespace Runn\tests\Fs\File;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exceptions\InvalidFile;
use Runn\Fs\File;

class FileTest extends TestCase
{

    protected $testCases = [];

    protected function setUp(): void
    {
        $this->testCases['file_exists'] = tempnam(sys_get_temp_dir(), 'FsTest');
        $this->testCases['dir_exists'] = sys_get_temp_dir() . '/FsTest_dir_exists';
        if (!file_exists($this->testCases['dir_exists'])) {
            mkdir($this->testCases['dir_exists'], 0777);
        }
    }

    protected function getPath($case)
    {
        return $this->testCases[$case];
    }

    public function testSetPathNotFile()
    {
        $this->expectException(InvalidFile::class);
        $file = new File;
        $file->setPath($this->getPath('dir_exists'));
    }

    public function testConstructNotFile()
    {
        $this->expectException(InvalidFile::class);
        $file = new File($this->getPath('dir_exists'));
    }

    public function testConstructRealFile()
    {
        $file = new File($this->getPath('file_exists'));
        $this->assertInstanceOf(File::class, $file);
        $this->assertTrue($file->isFile());
        $this->assertFalse($file->isDir());
        $this->assertSame($this->getPath('file_exists'), $file->getPath());
    }

    protected function tearDown(): void
    {
        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}
