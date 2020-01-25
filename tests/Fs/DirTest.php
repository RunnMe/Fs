<?php

namespace Runn\tests\Fs\Dir;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirAlreadyExists;
use Runn\Fs\Exceptions\DirNotReadable;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\InvalidDir;
use Runn\Fs\File;
use Runn\Fs\FileCollection;

class DirTest extends TestCase
{

    protected $testCases = [];

    protected function setUp(): void
    {
        $this->testCases['file_exists'] = tempnam(sys_get_temp_dir(), 'FsTest');
        $this->testCases['dir_exists'] = sys_get_temp_dir() . '/FsTest_dir_exists';
        if (!file_exists($this->testCases['dir_exists'])) {
            mkdir($this->testCases['dir_exists'], 0777);
        }

        $this->testCases['list'][1] = sys_get_temp_dir() . '/list/1';
        mkdir($this->testCases['list'][1], 0777, true);

        $this->testCases['list'][2] = sys_get_temp_dir() . '/list/2';
        mkdir($this->testCases['list'][2] . '/21', 0777, true);
        mkdir($this->testCases['list'][2] . '/22', 0777, true);

        $this->testCases['list'][3] = sys_get_temp_dir() . '/list/3';
        mkdir($this->testCases['list'][3] . '/31', 0777, true);
        mkdir($this->testCases['list'][3] . '/32', 0777, true);
        touch($this->testCases['list'][3] . '/33');

        $this->testCases['list'][4] = sys_get_temp_dir() . '/list/4';
        mkdir($this->testCases['list'][4] . '/41/1', 0777, true);
        mkdir($this->testCases['list'][4] . '/41/2', 0777, true);
        mkdir($this->testCases['list'][4] . '/42/2', 0777, true);
        touch($this->testCases['list'][4] . '/42/1');
        mkdir($this->testCases['list'][4] . '/43', 0777, true);
        touch($this->testCases['list'][4] . '/44');
    }

    protected function getPath($case)
    {
        return $this->testCases[$case];
    }

    public function testSetPathNotDir()
    {
        $this->expectException(InvalidDir::class);

        $dir = new Dir;
        $dir->setPath($this->getPath('file_exists'));
    }

    public function testConstructNotDir()
    {
        $this->expectException(InvalidDir::class);
        $dir = new Dir($this->getPath('file_exists'));
    }

    public function testConstructRealDir()
    {
        $dir = new Dir($this->getPath('dir_exists'));
        $this->assertInstanceOf(Dir::class, $dir);
        $this->assertFalse($dir->isFile());
        $this->assertTrue($dir->isDir());
        $this->assertSame($this->getPath('dir_exists'), $dir->getPath());
    }

    public function testCreateAlreadyExists()
    {
        $this->expectException(DirAlreadyExists::class);
        $dir = new Dir($this->getPath('dir_exists'));
        $dir->create();
    }

    public function testCreate()
    {
        $path = sys_get_temp_dir() . '/DirCreateTest';

        $dir = new Dir($path);
        $this->assertFalse($dir->exists());

        $res = $dir->create();
        $this->assertTrue($dir->exists());
        $this->assertTrue($dir->isDir());
        $this->assertSame($dir, $res);

        rmdir($path);
    }

    public function testListEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $dir = new Dir;
        $dir->list();
    }

    public function testListEmptyDir()
    {
        $dir = new Dir($this->getPath('list')[1]);

        $this->assertEquals(new FileCollection(), $dir->list());
        $this->assertEquals(new FileCollection(), $dir->list(false));

        $this->assertEquals(new FileCollection(), $dir->list(true));
    }

    public function testListNotReadableDir()
    {
        if (\Runn\Fs\isWindows()) {
            $this->assertTrue(true);
            return;
        }

        $path = $this->getPath('list')[1];
        chmod($path, 0000);

        try {

            $dir = new Dir($path);
            $dir->list();

        } catch (DirNotReadable $e) {
            $this->assertTrue(true);
        } finally {
            chmod($path, 0777);
        }
    }

    public function testListWithSubDirs()
    {
        $dir = new Dir(realpath($this->getPath('list')[2]));
        
        $subdirs = [
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR . '21'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR . '22'),
        ];

        $this->assertEquals(new FileCollection($subdirs), $dir->list());
        $this->assertEquals(new FileCollection($subdirs), $dir->list(false));

        $this->assertEquals(new FileCollection($subdirs), $dir->list(true));
    }
    public function testListWithSubDirsAndFiles()
    {
        $dir = new Dir(realpath($this->getPath('list')[3]));

        $subs = [
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '31'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '32'),
            new File($dir->getPath() . DIRECTORY_SEPARATOR . '33'),
        ];

        $this->assertEquals(new FileCollection($subs), $dir->list());
        $this->assertEquals(new FileCollection($subs), $dir->list(false));

        $this->assertEquals(new FileCollection($subs ), $dir->list(true));
    }

    public function testListRecursive()
    {
        $dir = new Dir(realpath($this->getPath('list')[4]));

        $subs = [
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '41'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '41' . DIRECTORY_SEPARATOR . '1'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '41' . DIRECTORY_SEPARATOR . '2'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '42'),
            new File($dir->getPath() . DIRECTORY_SEPARATOR .  '42' . DIRECTORY_SEPARATOR . '1'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR .  '42' . DIRECTORY_SEPARATOR . '2'),
            new Dir($dir->getPath() . DIRECTORY_SEPARATOR . '43'),
            new File($dir->getPath() . DIRECTORY_SEPARATOR . '44'),
        ];

        $this->assertEquals(new FileCollection([$subs[0], $subs[3], $subs[6], $subs[7]]), $dir->list());
        $this->assertEquals(new FileCollection([$subs[0], $subs[3], $subs[6], $subs[7]]), $dir->list(false));

        $this->assertEquals(new FileCollection($subs ), $dir->list(true));
    }

    protected function tearDown(): void
    {
        unlink($this->testCases['list'][4] . '/44');
        rmdir($this->testCases['list'][4] . '/43');
        rmdir($this->testCases['list'][4] . '/42/2');
        unlink($this->testCases['list'][4] . '/42/1');
        rmdir($this->testCases['list'][4] . '/42');
        rmdir($this->testCases['list'][4] . '/41/1');
        rmdir($this->testCases['list'][4] . '/41/2');
        rmdir($this->testCases['list'][4] . '/41');
        rmdir($this->testCases['list'][4]);

        unlink($this->testCases['list'][3] . '/33');
        rmdir($this->testCases['list'][3] . '/32');
        rmdir($this->testCases['list'][3] . '/31');
        rmdir($this->testCases['list'][3]);

        rmdir($this->testCases['list'][2] . '/22');
        rmdir($this->testCases['list'][2] . '/21');
        rmdir($this->testCases['list'][2]);

        rmdir($this->testCases['list'][1]);

        rmdir($this->testCases['dir_exists']);
        unlink($this->testCases['file_exists']);
    }

}