<?php

namespace Runn\tests\Fs\File;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Exceptions\EmptyPath;
use Runn\Fs\Exceptions\FileNotExists;
use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\Exceptions\FileNullContents;
use Runn\Fs\File;

class FileAsStorageTest extends TestCase
{

    public function testLoadEmptyPath()
    {
        $this->expectException(EmptyPath::class);
        $file = new File();
        $file->load();
    }

    public function testLoadNotExists()
    {
        $filename = sys_get_temp_dir() . '/Some/Fake/Dir/Which/Is/Not/Exist/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $this->expectException(FileNotExists::class);
        $file = new File($filename);
        $file->load();
    }

    public function testLoadNotReadable()
    {
        if (\Runn\Fs\isWindows()) {
            $this->assertTrue(true);
            return;
        }
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        chmod($filename, 0000);

        try {
            $file = new File($filename);
            $file->load();
        } catch (FileNotReadable $e) {
            $this->assertTrue(true);
            return;
        } finally {
            chmod($filename, 0777);
            unlink($filename);
        }

        $this->fail();
    }

    public function testLoadGetValid()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        file_put_contents($filename, 'Hello, world!');

        $file = new File($filename);
        $contents = new \ReflectionProperty(get_class($file), 'contents');
        $contents->setAccessible(true);

        $this->assertNull($contents->getValue($file));
        $this->assertNull($file->get());

        $file->load();

        $this->assertSame('Hello, world!', $contents->getValue($file));
        $this->assertSame('Hello, world!', $file->get());

        unlink($filename);
    }

    public function testReload()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        file_put_contents($filename, 'Hello, world!');

        $file = new File($filename);
        $contents = new \ReflectionProperty(get_class($file), 'contents');
        $contents->setAccessible(true);

        $file->load();

        $this->assertSame('Hello, world!', $contents->getValue($file));
        $this->assertSame('Hello, world!', $file->get());

        file_put_contents($filename, 'New content');

        $this->assertSame('Hello, world!', $contents->getValue($file));
        $this->assertSame('Hello, world!', $file->get());

        $file->load();

        $this->assertSame('New content', $contents->getValue($file));
        $this->assertSame('New content', $file->get());

        unlink($filename);
    }

    public function testSet()
    {
        $file = new File();
        $contents = new \ReflectionProperty(get_class($file), 'contents');
        $contents->setAccessible(true);

        $this->assertNull($contents->getValue($file));
        $this->assertNull($file->get());

        $file->set('Some value');

        $this->assertSame('Some value', $contents->getValue($file));
        $this->assertSame('Some value', $file->get());
    }

    public function testSaveNullContents()
    {
        $this->expectException(FileNullContents::class);
        $file = new File();
        $file->save();
    }

    public function testSaveNotWritable()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        chmod($filename, 0000);

        try {
            $file = new File($filename);
            $file->set('Hello, world!');
            $file->save();
        } catch (FileNotWritable $e) {
            $this->assertTrue(true);
            return;
        } finally {
            chmod($filename, 0777);
            unlink($filename);
        }

        $this->fail();
    }

    public function testSaveExists()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        $this->assertSame('', file_get_contents($filename));

        $file = new File($filename);
        $file->set('Hello, world')->save();

        $this->assertSame('Hello, world', file_get_contents($filename));

        $file->load();

        $this->assertSame('Hello, world', $file->get());
    }

    public function testSaveNotExists()
    {
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        unlink($filename);

        $file = new File($filename);
        $file->set('Hello, world')->save();

        $this->assertFileExists($filename);
        $this->assertSame('Hello, world', file_get_contents($filename));

        $file->load();

        $this->assertSame('Hello, world', $file->get());
    }

}
