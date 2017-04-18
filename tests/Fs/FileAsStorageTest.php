<?php

namespace Runn\tests\Fs\File;

use Runn\Fs\Exceptions\FileNotReadable;
use Runn\Fs\Exceptions\FileNotWritable;
use Runn\Fs\File;

class FileAsStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Fs\Exceptions\EmptyPath
     */
    public function testLoadEmptyPath()
    {
        $file = new File();
        $file->load();
        $this->fail();
    }

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNotExists
     */
    public function testLoadNotExists()
    {
        $filename = sys_get_temp_dir() . '/Some/Fake/Dir/Which/Is/Not/Exist/FsTest_touch';
        if (file_exists($filename)) {
            unlink($filename);
        }

        $file = new File($filename);
        $file->load();

        $this->fail();
    }

    public function testLoadNotReadable()
    {
        if ('Windows' == PHP_OS_FAMILY) {
            return;
        }
        $filename = tempnam(sys_get_temp_dir(), 'FsTest');
        chmod($filename, 0000);

        try {
            $file = new File($filename);
            $file->load();
        } catch (FileNotReadable $e) {
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

    /**
     * @expectedException \Runn\Fs\Exceptions\FileNullContents
     */
    public function testSaveNullContents()
    {
        $file = new File();
        $file->save();
        $this->fail();
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