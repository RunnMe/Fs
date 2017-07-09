<?php

namespace Runn\tests\Fs\Dir;

use Runn\Fs\Dir;
use Runn\Fs\FileCollection;

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

        $this->testCases['list'][1] = sys_get_temp_dir() . '/list/1';
        mkdir($this->testCases['list'][1], 0777, true);

        $this->testCases['list'][2] = sys_get_temp_dir() . '/list/2';
        mkdir($this->testCases['list'][2] . '/21' , 0777, true);
        mkdir($this->testCases['list'][2] . '/22' , 0777, true);
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
        $dir = new Dir;
        $dir->setPath($this->getPath('file_exists'));
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\InvalidDir
     */
    public function testConstructNotDir()
    {
        $dir = new Dir($this->getPath('file_exists'));
        $this->fail();
    }

    public function testConstructRealDir()
    {
        $dir = new Dir($this->getPath('dir_exists'));
        $this->assertInstanceOf(Dir::class, $dir);
        $this->assertFalse($dir->isFile());
        $this->assertTrue($dir->isDir());
        $this->assertSame($this->getPath('dir_exists'), $dir->getPath());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testListEmptyPath()
    {
        $dir = new Dir;
        $dir->list();
        $this->fail();
    }

    public function testListEmptyDir()
    {
        $dir = new Dir($this->getPath('list')[1]);

        $this->assertEquals(new FileCollection(), $dir->list());
        $this->assertEquals(new FileCollection(), $dir->list(false));
        $this->assertEquals(new FileCollection(), $dir->list(false, ''));
        $this->assertEquals(new FileCollection(), $dir->list(false, sys_get_temp_dir()));

        $this->assertEquals(new FileCollection(), $dir->list(true));
        $this->assertEquals(new FileCollection(), $dir->list(true, ''));
        $this->assertEquals(new FileCollection(), $dir->list(true, 'foo'));
        $this->assertEquals(new FileCollection(), $dir->list(true, sys_get_temp_dir()));
    }

    public function testListWithSubDirs()
    {
        $dir = new Dir(realpath($this->getPath('list')[2]));
        
        $pathpostfix = substr($dir->getPath(), strlen(sys_get_temp_dir()));

        $subdirs = [
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR . '21'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR . '22'),
        ];
        
        $subdirspostfixed = [
            new Dir($pathpostfix . DIRECTORY_SEPARATOR . '21'),
            new Dir($pathpostfix . DIRECTORY_SEPARATOR . '22'),
        ];

        $this->assertEquals(new FileCollection($subdirs), $dir->list());
        $this->assertEquals(new FileCollection($subdirs), $dir->list(false));
        $this->assertEquals(new FileCollection($subdirs), $dir->list(false, ''));
        $this->assertEquals(new FileCollection($subdirspostfixed), $dir->list(false, sys_get_temp_dir()));

        $this->assertEquals(new FileCollection($subdirs), $dir->list(true));
        $this->assertEquals(new FileCollection($subdirs), $dir->list(true, ''));
        $this->assertEquals(new FileCollection($subdirs), $dir->list(true, 'foo'));
        $this->assertEquals(new FileCollection($subdirspostfixed), $dir->list(true, sys_get_temp_dir()));
    }

    protected function tearDown()
    {
        rmdir($this->testCases['list'][2] . '/22');
        rmdir($this->testCases['list'][2] . '/21');
        rmdir($this->testCases['list'][2]);

        rmdir($this->testCases['list'][1]);

        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}