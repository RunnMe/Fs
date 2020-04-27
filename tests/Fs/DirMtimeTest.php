<?php

namespace Runn\tests\Fs\Dir;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Dir;
use Runn\Fs\Exceptions\DirNotExists;
use Runn\Fs\Exceptions\DirNotReadable;
use Runn\Fs\Exceptions\EmptyPath;

class DirMtimeTest extends TestCase
{

    public function testMtimeEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $dir = new Dir();
        $dir->mtime();
    }

    public function testMtimeNotExistingPath()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $this->expectException(DirNotExists::class);
        $dir = new Dir($path);
        $dir->mtime();
    }

    public function testMtimeNotReadable()
    {
        if (\Runn\Fs\isWindows()) {
            return;
        }

        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        try {

            mkdir($path, 0000);
            chmod($path, 0000);
            $dir = new Dir($path);
            $this->assertFalse($dir->isReadable());

            $dir->mtime();

        } catch (DirNotReadable $e) {
            return;
        } finally {
            chmod($path, 0777);
            rmdir($path);
        }
    }

    public function testMtimeEmptyDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        $dir = new Dir($path);
        mkdir($path);
        $this->assertTrue($dir->exists());
        $this->assertEqualsWithDelta(time(), $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time(), $dir->mtime(true, true),1);

        touch($path, time()-1000);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        rmdir($path);
    }

    public function testMtimeOnlyDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        touch($path . '/test.txt');

        $dir = new Dir($path);
        $this->assertTrue($dir->exists());
        $this->assertEqualsWithDelta(time(), $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time(), $dir->mtime(true, true), 1);

        touch($path, time()-1000);
        $this->assertEqualsWithDelta(time(), $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        unlink($path . '/test.txt');
        rmdir($path);
    }

    public function testMtimeNotEmptyDir()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        mkdir($path . '/1');
        mkdir($path . '/2');
        touch($path . '/3');
        touch($path . '/4');

        $dir = new Dir($path);
        $this->assertEqualsWithDelta(time(), $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time(), $dir->mtime(true, true), 1);

        touch($path, time()-1000);
        $this->assertEqualsWithDelta(time(), $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        touch($path . '/1', time()-10);
        touch($path . '/2', time()-20);
        touch($path . '/3', time()-30);
        touch($path . '/4', time()-40);

        $this->assertEqualsWithDelta(time()-10, $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        touch($path . '/1', time()-100);
        $this->assertEqualsWithDelta(time()-20, $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        unlink($path . '/4');
        unlink($path . '/3');
        rmdir($path . '/2');
        rmdir($path . '/1');
        rmdir($path);
    }

    public function testMtimeRecursive()
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('FsTest');
        $this->assertDirectoryNotExists($path);

        mkdir($path);
        mkdir($path . '/1');
        mkdir($path . '/1/11');
        mkdir($path . '/2');
        touch($path . '/1/3');
        touch($path . '/1/11/4');
        touch($path . '/2/5');

        touch($path, time()-1000);
        touch($path . '/1', time()-10);
        touch($path . '/1/11', time()-20);
        touch($path . '/2', time()-30);
        touch($path . '/1/3', time()-40);
        touch($path . '/1/11/4', time()-50);
        touch($path . '/2/5', time()-60);

        $dir = new Dir($path);

        $this->assertEqualsWithDelta(time()-10, $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        touch($path . '/1/11/4', time()-5);

        $this->assertEqualsWithDelta(time()-5, $dir->mtime(),  1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        touch($path . '/1/11/6');

        $this->assertEqualsWithDelta(time(), $dir->mtime(), 1);
        $this->assertEqualsWithDelta(time()-1000, $dir->mtime(true, true), 1);

        unlink($path . '/1/11/6');
        unlink($path . '/2/5');
        unlink($path . '/1/11/4');
        unlink($path . '/1/3');
        rmdir($path . '/2');
        rmdir($path . '/1/11');
        rmdir($path . '/1');
        rmdir($path);
    }

}
