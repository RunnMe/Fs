<?php

namespace Runn\tests\Fs\Dir;

use Runn\Fs\Dir;
use Runn\Fs\File;

class DirTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \Runn\Fs\Exceptions\InvalidDir
     */
    public function testSetPathNotDir()
    {
        $file = new Dir;
        $file->setPath($this->getPath('file_exists'));
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\InvalidDir
     */
    public function testConstructNotDir()
    {
        $file = new Dir($this->getPath('file_exists'));
        $this->fail();
    }

    public function testConstructRealDir()
    {
        $file = new Dir($this->getPath('dir_exists'));
        $this->assertInstanceOf(Dir::class, $file);
        $this->assertFalse($file->isFile());
        $this->assertTrue($file->isDir());
        $this->assertSame($this->getPath('dir_exists'), $file->getPath());
    }

    public function testList()
    {
        //var_dump((new Dir(__DIR__))->list());
    }

    protected function tearDown()
    {
        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}