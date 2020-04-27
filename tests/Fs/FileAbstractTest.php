<?php

namespace Runn\tests\Fs\FileAbstract;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\InvalidFileClass;
use Runn\Fs\File;
use Runn\Fs\FileAbstract;
use Runn\Fs\FileInterface;
use Runn\Fs\PathAwareInterface;

class FakeFileClass extends FileAbstract
{
    public function create() {
        return $this;
    }
    public function delete() {
        return $this;
    }
    public function mtime($clearstatcache = true) {
        return 0;
    }
}

class FileAbstractTest extends TestCase
{

    protected $testCases = [];

    protected function setUp(): void
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

    public function testFactoryFileInvalidClass()
    {
        $this->expectException(InvalidFileClass::class);
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

    public function testGetRealPath()
    {
        $obj = new FakeFileClass();

        $this->assertNull($obj->getRealPath());

        $obj->setPath('/foo/bar/baz');
        $this->assertNull($obj->getRealPath());

        $obj->setPath(__DIR__);
        $this->assertSame(__DIR__, $obj->getRealPath());

        $obj->setPath(__DIR__ . '/../' . basename(__DIR__));
        $this->assertSame(__DIR__, $obj->getRealPath());
    }

    public function testToString()
    {
        $randomPath = md5(time());
        $file = new FakeFileClass($randomPath);
        $this->assertSame(md5(time()), $file->getPath());
        $this->assertSame((string)$file, $file->getPath());
    }

    public function testEmptyExists()
    {
        $this->expectException(EmptyPath::class);
        $file = new FakeFileClass;
        $res = $file->exists();
    }

    public function testExists()
    {
        $file = new FakeFileClass($this->getPath('file_exists'));
        $this->assertTrue($file->exists());

        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $this->assertFalse($file->exists());
    }

    public function testIsFileEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new FakeFileClass;
        $res = $file->isFile();
    }

    public function testIsFileNotExists()
    {
        $this->expectException(FileNotExists::class);
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isFile();
    }

    public function testIsDirEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new FakeFileClass;
        $res = $file->isDir();
    }

    public function testIsDirNotExists()
    {
        $this->expectException(FileNotExists::class);
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isDir();
    }

    public function testIsLinkEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new FakeFileClass;
        $res = $file->isLink();
        $this->fail();
    }

    public function testIsLinkNotExists()
    {
        $this->expectException(FileNotExists::class);
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isLink();
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

    public function testIsReadableEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new FakeFileClass;
        $res = $file->isReadable();
    }

    public function testIsReadableNotExists()
    {
        $this->expectException(FileNotExists::class);
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isReadable();
    }

    public function testIsReadable()
    {
        if (\Runn\Fs\isWindows()) {
            $this->assertTrue(true);
            return;
        }

        $file = new FakeFileClass($this->getPath('file_exists'));
        $this->assertTrue($file->isReadable());

        $file = new FakeFileClass($this->getPath('file_not_readable'));
        $this->assertFalse($file->isReadable());
    }

    public function testIsWritableEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new FakeFileClass;
        $res = $file->isWritable();
    }

    public function testIsWritableNotExists()
    {
        $this->expectException(FileNotExists::class);
        $file = new FakeFileClass($this->getPath('file_not_exists'));
        $res = $file->isWritable();
    }

    public function testIsWritable()
    {
        $file = new FakeFileClass($this->getPath('file_exists'));
        $this->assertTrue($file->isWritable());

        $file = new FakeFileClass($this->getPath('file_not_writable'));
        $this->assertFalse($file->isWritable());
    }

    protected function tearDown(): void
    {
        chmod($this->testCases['file_not_readable'], 0777);
        unlink($this->testCases['file_not_readable']);
        unlink($this->testCases['file_link_exists']);
        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}
