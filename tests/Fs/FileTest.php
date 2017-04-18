<?php

namespace Runn\tests\Fs\File;

use Runn\Fs\File;
use Runn\Fs\FileAsStorageInterface;
use Runn\Storages\SingleValueStorageInterface;

class FileTest extends \PHPUnit_Framework_TestCase
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

    public function testConstructEmpty()
    {
        $file = new File;
        $this->assertInstanceOf(File::class, $file);
        $this->assertInstanceOf(FileAsStorageInterface::class, $file);
        $this->assertInstanceOf(SingleValueStorageInterface::class, $file);
        $this->assertNull($file->getPath());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testEmptyExists()
    {
        $file = new File;
        $res = $file->exists();
        $this->fail();
    }

    public function testExists()
    {
        $file = new File($this->getPath('file_exists'));
        $this->assertTrue($file->exists());

        $file = new File($this->getPath('file_not_exists'));
        $this->assertFalse($file->exists());
    }

    public function testSetGetPath()
    {
        $path1 = 'TestFs1';
        $path2 = 'TestFs2';

        $file = new File($path1);
        $this->assertSame($path1, $file->getPath());

        $file = new File;
        $file->setPath($path2);
        $this->assertSame($path2, $file->getPath());
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsFileEmptyPath()
    {
        $file = new File;
        $res = $file->isFile();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsFileNotExists()
    {
        $file = new File($this->getPath('file_not_exists'));
        $res = $file->isFile();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsDirEmptyPath()
    {
        $file = new File;
        $res = $file->isDir();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsDirNotExists()
    {
        $file = new File($this->getPath('file_not_exists'));
        $res = $file->isDir();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsLinkEmptyPath()
    {
        $file = new File;
        $res = $file->isLink();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsLinkNotExists()
    {
        $file = new File($this->getPath('file_not_exists'));
        $res = $file->isLink();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsReadableEmptyPath()
    {
        $file = new File;
        $res = $file->isReadable();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsReadableNotExists()
    {
        $file = new File($this->getPath('file_not_exists'));
        $res = $file->isReadable();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testIsWritableEmptyPath()
    {
        $file = new File;
        $res = $file->isWritable();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testIsWritableNotExists()
    {
        $file = new File($this->getPath('file_not_exists'));
        $res = $file->isWritable();
        $this->fail();
    }

    public function testIsFileTrue()
    {
        $file = new File($this->getPath('file_exists'));

        $this->assertTrue($file->isFile());
        $this->assertFalse($file->isDir());
        $this->assertFalse($file->isLink());
    }

    public function testIsDirTrue()
    {
        $file = new File($this->getPath('dir_exists'));

        $this->assertFalse($file->isFile());
        $this->assertTrue($file->isDir());
        $this->assertFalse($file->isLink());
    }

    public function testIsLinkTrue()
    {
        $file = new File($this->getPath('file_link_exists'));

        $this->assertTrue($file->isFile());
        $this->assertFalse($file->isDir());
        $this->assertTrue($file->isLink());
    }

    public function testIsReadable()
    {
        /** @todo @7.2 PHP_OS_FAMILY  == 'Windows' */
        if (in_array(PHP_OS, ['WIN32', 'WINNT', 'Windows'])) {
            return;
        }

        $file = new File($this->getPath('file_exists'));
        $this->assertTrue($file->isReadable());

        $file = new File($this->getPath('file_not_readable'));
        $this->assertFalse($file->isReadable());
    }

    public function testIsWritable()
    {
        $file = new File($this->getPath('file_exists'));
        $this->assertTrue($file->isWritable());

        $file = new File($this->getPath('file_not_writable'));
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