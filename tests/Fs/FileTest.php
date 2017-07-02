<?php

namespace Runn\tests\Fs\File;

use Runn\Fs\File;

class FileTest extends \PHPUnit_Framework_TestCase
{

    protected $testCases = [];

    protected function setUp()
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

    /**
     * @expectedException \Runn\Fs\Exceptions\InvalidFile
     */
    public function testSetPathNotFile()
    {
        $file = new File;
        $file->setPath($this->getPath('dir_exists'));
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\InvalidFile
     */
    public function testConstructNotFile()
    {
        $file = new File($this->getPath('dir_exists'));
        $this->fail();
    }

    public function testConstructRealFile()
    {
        $file = new File($this->getPath('file_exists'));
        $this->assertInstanceOf(File::class, $file);
        $this->assertSame($this->getPath('file_exists'), $file->getPath());
    }

    protected function tearDown()
    {
        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}