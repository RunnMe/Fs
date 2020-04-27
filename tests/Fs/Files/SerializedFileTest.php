<?php

namespace Runn\tests\Fs\Files\SerializedFile;

use PHPUnit\Framework\TestCase;
use Runn\Fs\Files\SerializedFile;
use Runn\Serialization\Serializers\PassThru;

class SerializedFileTest extends TestCase
{

    public function testSetSerializer()
    {
        $this->expectException(\BadMethodCallException::class);
        $file = new SerializedFile();
        $file->setSerializer(new PassThru());
    }

    public function testSave()
    {
        $filename = sys_get_temp_dir() . '/FsTest_save.php';

        $file = new SerializedFile($filename);
        $file->set(42)->save();
        $this->assertSame('i:42;', file_get_contents($filename));

        $file = new SerializedFile($filename);
        $file->set('foo')->save();
        $this->assertSame('s:3:"foo";', file_get_contents($filename));

        $file = new SerializedFile($filename);
        $file->set([1, 2, 3])->save();
        $this->assertSame('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}', file_get_contents($filename));

        unlink($filename);
    }

    public function testLoad()
    {
        $filename = sys_get_temp_dir() . '/FsTest_save.php';

        file_put_contents($filename, 'i:42;');
        $file = new SerializedFile($filename);
        $contents = $file->load()->get();
        $this->assertSame(42, $contents);

        file_put_contents($filename, 's:3:"foo";');
        $file = new SerializedFile($filename);
        $contents = $file->load()->get();
        $this->assertSame('foo', $contents);

        file_put_contents($filename, 'a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}');
        $file = new SerializedFile($filename);
        $contents = $file->load()->get();
        $this->assertSame([1, 2, 3], $contents);

        unlink($filename);
    }

}
