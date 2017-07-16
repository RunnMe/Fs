<?php

namespace Runn\tests\Fs\FileAbstract;

use Runn\Fs\Dir;
use Runn\Fs\File;
use Runn\Fs\FileAbstract;
use Runn\Fs\FileInterface;
use Runn\Fs\PathAwareInterface;

class FakeFileClass extends FileAbstract
{
    public function create() {
        return $this;
    }
    public function mtime($clearstatcache = true) {
        return 0;
    }
}

class FileAbstractTest extends \PHPUnit_Framework_TestCase
{

    protected $testCases = [];

    protected function setUp()
    {
        $this->testCases['file_exists'] = tempnam(sys_get_temp_dir(), 'FsTest');
        $this->testCases['file_not_exists'] = $this->testCases['file_exists'] . 'notexists';
        $this->testCases['dir_exists'] = sys_get_temp_dir() . '/FsTest_dir_exists';
        if (!file_exists($this->testCases['dir_exists'])) {
            mkdir($this->testCases['dir_exists'], 0777);
        }
        $this->testCases['file_link_exists'] = $this->testCases['file_exists'] . 'link';
        symlink($this->testCases['file_exists'], $this->testCases['file_link_exists']);
        $this->testCases['file_not_readable'] = tempnam(sys_get_temp_dir(), 'FsTest');
        chmod($this->testCases['file_not_readable'], 0000);
        $this->testCases['file_not_writable'] = $this->testCases['file_not_readable'];
    }

    protected function getPath($case)
    {
        return $this->testCases[$case];
    }

    public function testFactoryFileNotExists()
    {
        $file = FileAbstract::instance($this->getPath('file_not_exists'));
        $this->assertInstanceOf(File::class, $file);
        $this->assertSame($this->getPath('file_not_exists'), $file->getPath());
        $this->assertFalse($file->exists());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\InvalidFileClass
     */
    public function testFactoryFileInvalidClass()
    {
        $file = FileAbstract::instance($this->getPath('file_exists'), \stdClass::class);
    }

    public function testFactoryFile()
    {
        $file = FileAbstract::instance($this->getPath('file_exists'));
        $this->assertInstanceOf(File::class, $file);
        $this->assertTrue($file->isFile());
        $this->assertSame($this->getPath('file_exists'), $file->getPath());

        $file = FileAbstract::instance($this->getPath('file_exists'), FakeFileClass::class);
        $this->assertInstanceOf(FakeFileClass::class, $file);
        $this->assertTrue($file->isFile());
        $this->assertSame($this->getPath('file_exists'), $file->getPath());
    }

    public function testFactoryDir()
    {
        $file = FileAbstract::instance($this->getPath('dir_exists'));
        $this->assertInstanceOf(Dir::class, $file);
        $this->assertTrue($file->isDir());
        $this->assertSame($this->getPath('dir_exists'), $file->getPath());

        $file = FileAbstract::instance($this->getPath('dir_exists'), FakeFileClass::class);
        $this->assertInstanceOf(FakeFileClass::class, $file);
        $this->assertTrue($file->isDir());
        $this->assertSame($this->getPath('dir_exists'), $file->getPath());
    }

    public function testConstructEmpty()
    {
        $file = new FakeFileClass();
        $this->assertInstanceOf(FileInterface::class, $file);
        $this->assertInstanceOf(PathAwareInterface::class, $file);
        $this->assertInstanceOf(FileAbstract::class, $file);
        $this->assertSame('', $file->getPath());
    }

    public function testSetGetPath()
    {
        $path1 = 'TestFs1';
        $path2 = 'TestFs2';

        $file = new FakeFileClass($path1);
        $this->assertSame($path1, $file->getPath());

        $file = new FakeFileClass;
        $file->setPath($path2);
        $this->assertSame($path2, $file->getPath());
    }

    public function testToString()
    {
        $randomPath = md5(time());
        $file = new FakeFileClass($randomPath);
        $this->assertSame(md5(time()), $file->getPath());
        $this->assertSame((string)$file, $file->getPath());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testEmptyExists()
    {
        $file = new FakeFileClass;
        $res = $file->exists();
        $this->fail();
    }

    public function testExists()
    {
        $file = new FakeFileClass($this->getPath('file_exists'));
        $this->assertTrue($file->exists());

        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $this->assertFalse($file->exists());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsFileEmptyPath()
    {
        $file = new FakeFileClass;
        $res = $file->isFile();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsFileNotExists()
    {
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isFile();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsDirEmptyPath()
    {
        $file = new FakeFileClass;
        $res = $file->isDir();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsDirNotExists()
    {
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isDir();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsLinkEmptyPath()
    {
        $file = new FakeFileClass;
        $res = $file->isLink();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsLinkNotExists()
    {
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isLink();
        $this->fail();
    }

    public function testIsFileTrue()
    {
        $file = new FakeFileClass($this->getPath('file_exists'));

        $this->assertTrue($file->isFile());
        $this->assertFalse($file->isDir());
        $this->assertFalse($file->isLink());
    }

    public function testIsDirTrue()
    {
        $file = new FakeFileClass($this->getPath('dir_exists'));

        $this->assertFalse($file->isFile());
        $this->assertTrue($file->isDir());
        $this->assertFalse($file->isLink());
    }

    public function testIsLinkTrue()
    {
        $file = new FakeFileClass($this->getPath('file_link_exists'));

        $this->assertTrue($file->isFile());
        $this->assertFalse($file->isDir());
        $this->assertTrue($file->isLink());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsReadableEmptyPath()
    {
        $file = new FakeFileClass;
        $res = $file->isReadable();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsReadableNotExists()
    {
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isReadable();
        $this->fail();
    }

    public function testIsReadable()
    {
        /** @todo @7.2 PHP_OS_FAMILY  == 'Windows' */
        if (in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            return;
        }

        $file = new FakeFileClass($this->getPath('file_exists'));
        $this->assertTrue($file->isReadable());

        $file = new FakeFileClass($this->getPath('file_not_readable'));
        $this->assertFalse($file->isReadable());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsWritableEmptyPath()
    {
        $file = new FakeFileClass;
        $res = $file->isWritable();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsWritableNotExists()
    {
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isWritable();
        $this->fail();
    }

    public function testIsWritable()
    {
        $file = new FakeFileClass($this->getPath('file_exists'));
        $this->assertTrue($file->isWritable());

        $file = new FakeFileClass($this->getPath('file_not_writable'));
        $this->assertFalse($file->isWritable());
    }

    protected function tearDown()
    {
        chmod($this->testCases['file_not_readable'], 0777);
        unlink($this->testCases['file_not_readable']);
        unlink($this->testCases['file_link_exists']);
        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}